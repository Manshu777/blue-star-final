<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleriesResource\Pages;
use App\Models\Galleries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleriesResource extends Resource
{
    protected static ?string $model = Galleries::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Upload Image/Video')
                    ->disk('public')
                    ->directory('gallery')
                    ->visibility('public')
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'video/mp4',
                        'video/mpeg',
                        'video/quicktime',
                    ])
                    ->maxSize(102400)
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('File Type')
                    ->options([
                        'photo' => 'Photo',
                        'video' => 'Video',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                // Image preview
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Image')
                    ->disk('public')
                    ->height(80)
                    ->visible(fn($record) => $record?->type === 'photo'),

                // Video preview (playable)
                Tables\Columns\ViewColumn::make('file_path')
                    ->label('Video')
                    ->view('filament.tables.columns.video-preview')
                    ->visible(fn($record) => $record?->type === 'video'),

                Tables\Columns\TextColumn::make('type')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGalleries::route('/create'),
            'view' => Pages\ViewGalleries::route('/{record}'),
            'edit' => Pages\EditGalleries::route('/{record}/edit'),
        ];
    }
}
