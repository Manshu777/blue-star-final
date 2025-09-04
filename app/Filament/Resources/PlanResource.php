<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('monthly_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
                Forms\Components\TextInput::make('yearly_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0),
                Forms\Components\Select::make('billing_cycle')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('storage_limit')
                    ->required()
                    ->numeric()
                    ->suffix('GB')
                    ->minValue(0),
                Forms\Components\TextInput::make('photo_upload_limit')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Toggle::make('facial_recognition_enabled')
                    ->required(),
                Forms\Components\Toggle::make('merchandise_enabled')
                    ->required(),
                Forms\Components\KeyValue::make('description')
                    ->label('Features')
                    ->keyLabel('Feature')
                    ->valueLabel('Description'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monthly_price')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('yearly_price')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('storage_limit')
                    ->suffix('GB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('photo_upload_limit')
                    ->sortable(),
                Tables\Columns\IconColumn::make('facial_recognition_enabled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('merchandise_enabled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('facial_recognition_enabled'),
                Tables\Filters\TernaryFilter::make('merchandise_enabled'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
