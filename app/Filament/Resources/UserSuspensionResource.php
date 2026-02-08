<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSuspensionResource\Pages;
use App\Filament\Resources\UserSuspensionResource\RelationManagers;
use App\Models\UserSuspension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSuspensionResource extends Resource
{
    protected static ?string $model = UserSuspension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->columnSpanFull()
                    ->label('Suspension Reason'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expires At (Optional)')
                    ->helperText('Leave empty for permanent suspension')
                    ->minDate(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ->label('User ID'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->reason),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Expires At')
                    ->placeholder('Permanent'),
            ])
            ->defaultSort('user_id', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserSuspensions::route('/'),
            'create' => Pages\CreateUserSuspension::route('/create'),
            'view' => Pages\ViewUserSuspension::route('/{record}'),
            'edit' => Pages\EditUserSuspension::route('/{record}/edit'),
        ];
    }
}
