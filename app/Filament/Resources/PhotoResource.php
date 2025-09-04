<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoResource\Pages;
use App\Filament\Resources\PhotoResource\RelationManagers;
use App\Models\Photo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PhotoResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
       return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\FileUpload::make('image_path')
                    ->required()
                    ->disk('s3')
                    ->directory('photos')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->image()
                    ->maxSize(10240), // 10MB max
                Forms\Components\FileUpload::make('watermarked_path')
                    ->disk('s3')
                    ->directory('photos/watermarked')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->image()
                    ->maxSize(10240),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\Select::make('license_type')
                    ->options(['commercial' => 'Commercial', 'editorial' => 'Editorial'])
                    ->required(),
                Forms\Components\TagsInput::make('tags'),
                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadata'),
                Forms\Components\TextInput::make('tour_provider')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('event')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date'),
                Forms\Components\TextInput::make('file_size')
                    ->numeric()
                    ->suffix('MB')
                    ->default(0),
            ]);
    }

 public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('s3'),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('license_type'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('file_size')
                    ->suffix(' MB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),
                Tables\Filters\SelectFilter::make('location')
                    ->options(fn () => Photo::distinct('location')->pluck('location', 'location')),
                Tables\Filters\TernaryFilter::make('is_featured'),
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
            'index' => Pages\ListPhotos::route('/'),
            'create' => Pages\CreatePhoto::route('/create'),
            'edit' => Pages\EditPhoto::route('/{record}/edit'),
        ];
    }
}
