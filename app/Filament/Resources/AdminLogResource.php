<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminLogResource\Pages;
use App\Filament\Resources\AdminLogResource\RelationManagers;
use App\Models\AdminLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminLogResource extends Resource
{
    protected static ?string $model = AdminLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('admin_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('action')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('target_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('target_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admin_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListAdminLogs::route('/'),
            'create' => Pages\CreateAdminLog::route('/create'),
            'view' => Pages\ViewAdminLog::route('/{record}'),
            'edit' => Pages\EditAdminLog::route('/{record}/edit'),
        ];
    }
}
