<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Relationships')
                    ->schema([
                        Select::make('country_id')
                            ->relationship(name:'country',titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set)
                            {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required()
                            ->native(false),

                        Select::make('state_id')
                            ->options(fn(Forms\Get $get):Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name','id'))
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('State')
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('city_id', null)),

                        Select::make('city_id')
                            ->options(fn(Forms\Get $get):Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name','id'))
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('City'),

                        Select::make('department_id')
                            ->relationship(name:'department',titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Section::make('User Name')
                    ->description('Put the user name details in.')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('middle_name')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),

                Section::make('User address')
                    ->schema([
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('zip_code')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Dates')
                    ->schema([
                        DatePicker::make('date_of_birth')
                            ->required(),
                        DatePicker::make('date_hired')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden(true),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
