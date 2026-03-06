<?php

namespace App\Filament\Resources\GiftCardCategories;

use App\Filament\Resources\GiftCardCategories\Pages\CreateGiftCardCategory;
use App\Filament\Resources\GiftCardCategories\Pages\EditGiftCardCategory;
use App\Filament\Resources\GiftCardCategories\Pages\ListGiftCardCategories;
use App\Filament\Resources\GiftCardCategories\Schemas\GiftCardCategoryForm;
use App\Filament\Resources\GiftCardCategories\Tables\GiftCardCategoriesTable;
use App\Models\GiftCardCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GiftCardCategoryResource extends Resource
{
    protected static ?string $model = GiftCardCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Gift Card Categories';

    public static function form(Schema $schema): Schema
    {
        return GiftCardCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GiftCardCategoriesTable::configure($table);
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
            'index' => ListGiftCardCategories::route('/'),
            'create' => CreateGiftCardCategory::route('/create'),
            'edit' => EditGiftCardCategory::route('/{record}/edit'),
        ];
    }
}
