<?php

namespace App\Filament\Resources\GiftCardCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GiftCardCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('logo')
                    ->image() // Only accept images
                    ->directory('categories') // Store in storage/categories/
                    ->maxSize(1024) // Max 1MB
                    ->default(null),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('min_value')
                    ->required()
                    ->numeric()
                    ->default(5.0),
                TextInput::make('max_value')
                    ->required()
                    ->numeric()
                    ->default(500.0),
                TextInput::make('platform_rate')
                    ->required()
                    ->numeric()
                    ->default(80.0),
                TextInput::make('commission_rate')
                    ->required()
                    ->numeric()
                    ->default(5.0),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('platform_buying')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('denominations')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
