<?php

namespace App\Filament\Resources\GiftCards\Schemas;

use App\Models\GiftCard;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GiftCardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('card_number')
                    ->placeholder('-'),
                TextEntry::make('card_pin')
                    ->placeholder('-'),
                TextEntry::make('card_code')
                    ->placeholder('-'),
                TextEntry::make('face_value')
                    ->numeric(),
                TextEntry::make('selling_price')
                    ->money(),
                TextEntry::make('currency'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('trade_type'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('images')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('verification_status'),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('verified_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('listed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('metadata')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (GiftCard $record): bool => $record->trashed()),
            ]);
    }
}
