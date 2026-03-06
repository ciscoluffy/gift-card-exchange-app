<?php

namespace App\Filament\Resources\GiftCards\Pages;

use App\Filament\Resources\GiftCards\GiftCardResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGiftCard extends ViewRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
