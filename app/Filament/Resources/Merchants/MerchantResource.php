<?php

namespace App\Filament\Resources\Merchants;

use App\Filament\Resources\Merchants\Pages\CreateMerchant;
use App\Filament\Resources\Merchants\Pages\EditMerchant;
use App\Filament\Resources\Merchants\Pages\ListMerchants;
use App\Filament\Resources\Merchants\Schemas\MerchantForm;
use App\Filament\Resources\Merchants\Tables\MerchantsTable;
use App\Models\Merchant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MerchantResource extends Resource
{
    protected static ?string $model = Merchant::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    protected static ?string $tenantOwnershipRelationshipName = 'wallet';

    public static function getModelLabel(): string
    {
        return __('Merchant');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Merchants');
    }

    public static function form(Schema $schema): Schema
    {
        return MerchantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MerchantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMerchants::route('/'),
            'create' => CreateMerchant::route('/create'),
            'edit' => EditMerchant::route('/{record}/edit'),
        ];
    }
}
