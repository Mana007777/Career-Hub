<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\AdminLog;
use App\Models\Post;
use App\Models\PostSuspension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Author')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('job_type')
                            ->options([
                                'full-time' => 'Full Time',
                                'part-time' => 'Part Time',
                                'contract' => 'Contract',
                                'freelance' => 'Freelance',
                                'internship' => 'Internship',
                            ])
                            ->placeholder('Select job type')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('media')
                            ->label('Media URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/image.jpg')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('users', 'posts.user_id', '=', 'users.id')
                            ->orderBy('users.name', $direction)
                            ->select('posts.*');
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('content')
                    ->limit(30)
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'full-time' => 'success',
                        'part-time' => 'info',
                        'contract' => 'warning',
                        'freelance' => 'gray',
                        'internship' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\IconColumn::make('media')
                    ->label('Has Media')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->media)),
                // Likes removed – only show comments and suspension info
                Tables\Columns\TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable(),
                Tables\Columns\IconColumn::make('suspension')
                    ->label('Suspended')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->suspension !== null)
                    ->color(fn ($record) => $record->suspension ? 'danger' : 'success')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('job_type')
                    ->options([
                        'full-time' => 'Full Time',
                        'part-time' => 'Part Time',
                        'contract' => 'Contract',
                        'freelance' => 'Freelance',
                        'internship' => 'Internship',
                    ]),
                Tables\Filters\Filter::make('has_media')
                    ->label('Has Media')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('media')->where('media', '!=', '')),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make()
                        ->label('Remove')
                        ->requiresConfirmation()
                        ->modalHeading('Remove Post')
                        ->modalDescription('Are you sure you want to remove this post? This action cannot be undone.')
                        ->action(function (Post $record) {
                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Removed post: ' . ($record->title ?: 'Post #' . $record->id),
                                'target_type' => Post::class,
                                'target_id' => $record->id,
                            ]);
                            
                            $record->delete();
                        }),
                    Tables\Actions\Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-lock-closed')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('reason')
                                ->label('Suspension Reason')
                                ->required()
                                ->rows(3)
                                ->placeholder('Enter the reason for suspending this post...'),
                            Forms\Components\DateTimePicker::make('expires_at')
                                ->label('Expires At (Optional)')
                                ->helperText('Leave empty for permanent suspension')
                                ->minDate(now()),
                        ])
                        ->modalHeading('Suspend Post')
                        ->modalDescription('Suspend this post. It will be hidden from public view.')
                        ->action(function (Post $record, array $data) {
                            PostSuspension::updateOrCreate(
                                ['post_id' => $record->id],
                                [
                                    'reason' => $data['reason'],
                                    'expires_at' => $data['expires_at'] ?? null,
                                ]
                            );

                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Suspended post: ' . ($record->title ?: 'Post #' . $record->id) . ' - Reason: ' . $data['reason'],
                                'target_type' => Post::class,
                                'target_id' => $record->id,
                            ]);
                        })
                        ->successNotificationTitle('Post suspended successfully'),
                    Tables\Actions\Action::make('unsuspend')
                        ->label('Unsuspend')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->visible(fn (Post $record) => $record->suspension !== null)
                        ->requiresConfirmation()
                        ->modalHeading('Unsuspend Post')
                        ->modalDescription('Remove the suspension from this post. It will be visible to users again.')
                        ->action(function (Post $record) {
                            $record->suspension?->delete();

                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Unsuspended post: ' . ($record->title ?: 'Post #' . $record->id),
                                'target_type' => Post::class,
                                'target_id' => $record->id,
                            ]);
                        })
                        ->successNotificationTitle('Post unsuspended successfully'),
                ])
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
