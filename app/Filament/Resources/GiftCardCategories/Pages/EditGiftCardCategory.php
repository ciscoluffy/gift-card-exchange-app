<?php

namespace App\Filament\Resources\GiftCardCategories\Pages;

use App\Filament\Resources\GiftCardCategories\GiftCardCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGiftCardCategory extends EditRecord
{
    protected static string $resource = GiftCardCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
