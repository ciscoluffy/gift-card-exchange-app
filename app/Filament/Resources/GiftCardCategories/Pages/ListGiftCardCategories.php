<?php

namespace App\Filament\Resources\GiftCardCategories\Pages;

use App\Filament\Resources\GiftCardCategories\GiftCardCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGiftCardCategories extends ListRecords
{
    protected static string $resource = GiftCardCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
