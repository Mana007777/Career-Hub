<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\AdminLog;
use App\Models\User;
use App\Models\UserSuspension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('username')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Forms\Components\Select::make('role')
                            ->options([
                                'seeker' => 'Seeker',
                                'employer' => 'Employer',
                                'admin' => 'Admin',
                            ])
                            ->required()
                            ->default('seeker')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_admin')
                            ->label('Is Admin')
                            ->helperText('Grant admin access to this user')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->visibleOn(['create', 'edit']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'employer' => 'warning',
                        'seeker' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin')
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->email_verified_at))
                    ->sortable(),
                Tables\Columns\TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts')
                    ->sortable(),
                Tables\Columns\IconColumn::make('suspension')
                    ->label('Suspended')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->suspension !== null)
                    ->color(fn ($record) => $record->suspension ? 'danger' : 'success')
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
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'seeker' => 'Seeker',
                        'employer' => 'Employer',
                        'admin' => 'Admin',
                    ]),
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Admin Users')
                    ->placeholder('All users')
                    ->trueLabel('Admin users only')
                    ->falseLabel('Non-admin users only'),
                Tables\Filters\Filter::make('email_verified_at')
                    ->label('Email Verified')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make()
                        ->label('Remove')
                        ->requiresConfirmation()
                        ->modalHeading('Remove User')
                        ->modalDescription('Are you sure you want to remove this user? This action cannot be undone.')
                        ->action(function (User $record) {
                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Removed user: ' . $record->name,
                                'target_type' => User::class,
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
                                ->placeholder('Enter the reason for suspending this user...'),
                            Forms\Components\DateTimePicker::make('expires_at')
                                ->label('Expires At (Optional)')
                                ->helperText('Leave empty for permanent suspension')
                                ->minDate(now()),
                        ])
                        ->modalHeading('Suspend User')
                        ->modalDescription('Suspend this user from the platform. They will not be able to access their account.')
                        ->action(function (User $record, array $data) {
                            UserSuspension::updateOrCreate(
                                ['user_id' => $record->id],
                                [
                                    'reason' => $data['reason'],
                                    'expires_at' => $data['expires_at'] ?? null,
                                ]
                            );

                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Suspended user: ' . $record->name . ' - Reason: ' . $data['reason'],
                                'target_type' => User::class,
                                'target_id' => $record->id,
                            ]);
                        })
                        ->successNotificationTitle('User suspended successfully'),
                    Tables\Actions\Action::make('unsuspend')
                        ->label('Unsuspend')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->visible(fn (User $record) => $record->suspension !== null)
                        ->requiresConfirmation()
                        ->modalHeading('Unsuspend User')
                        ->modalDescription('Remove the suspension from this user. They will regain access to their account.')
                        ->action(function (User $record) {
                            $record->suspension?->delete();

                            // Log admin action
                            AdminLog::create([
                                'admin_id' => auth()->id(),
                                'action' => 'Unsuspended user: ' . $record->name,
                                'target_type' => User::class,
                                'target_id' => $record->id,
                            ]);
                        })
                        ->successNotificationTitle('User unsuspended successfully'),
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
            RelationManagers\PostsRelationManager::class,
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
