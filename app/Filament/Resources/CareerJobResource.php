<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerJobResource\Pages;
use App\Filament\Resources\CareerJobResource\RelationManagers;
use App\Models\CareerJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CareerJobResource extends Resource
{
    protected static ?string $model = CareerJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Job Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Job Information')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('specialty_id')
                            ->label('Specialty')
                            ->relationship('specialty', 'name')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('sub_specialty_id', null))
                            ->columnSpan(1),
                        Forms\Components\Select::make('sub_specialty_id')
                            ->label('Sub Specialty')
                            ->relationship('subSpecialty', 'name', fn ($query, $get) => 
                                $query->where('specialty_id', $get('specialty_id'))
                            )
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('job_type')
                            ->options([
                                'full-time' => 'Full Time',
                                'part-time' => 'Part Time',
                                'contract' => 'Contract',
                                'freelance' => 'Freelance',
                                'internship' => 'Internship',
                            ])
                            ->required()
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialty.name')
                    ->label('Specialty')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subSpecialty.name')
                    ->label('Sub Specialty')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'full-time' => 'success',
                        'part-time' => 'info',
                        'contract' => 'warning',
                        'freelance' => 'gray',
                        'internship' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications')
                    ->sortable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->counts('views')
                    ->label('Views')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
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
                Tables\Filters\SelectFilter::make('specialty_id')
                    ->label('Specialty')
                    ->relationship('specialty', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareerJobs::route('/'),
            'create' => Pages\CreateCareerJob::route('/create'),
            'view' => Pages\ViewCareerJob::route('/{record}'),
            'edit' => Pages\EditCareerJob::route('/{record}/edit'),
        ];
    }
}
