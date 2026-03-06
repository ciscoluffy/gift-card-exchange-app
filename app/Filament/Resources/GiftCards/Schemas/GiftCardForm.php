<?php

namespace App\Filament\Resources\GiftCards\Schemas;

use App\Enums\GiftCardStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GiftCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('card_number')
                    ->default(null),
                TextInput::make('card_pin')
                    ->default(null),
                TextInput::make('card_code')
                    ->default(null),
                TextInput::make('face_value')
                    ->required()
                    ->numeric(),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                Select::make('status')
                    ->options(GiftCardStatus::class)
                    ->default('draft')
                    ->required(),
                TextInput::make('trade_type')
                    ->required()
                    ->default('sell'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('images')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('verification_status')
                    ->required()
                    ->default('pending'),
                Textarea::make('rejection_reason')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('verified_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('verified_at'),
                DateTimePicker::make('listed_at'),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
