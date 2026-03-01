<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\InstallmentPurchases;

use App\Filament\Resources\InstallmentPurchases\Pages\CreateInstallmentPurchase;
use App\Filament\Resources\InstallmentPurchases\Pages\EditInstallmentPurchase;
use App\Filament\Resources\InstallmentPurchases\Pages\ListInstallmentPurchases;
use App\Filament\Resources\InstallmentPurchases\Schemas\InstallmentPurchaseForm;
use App\Filament\Resources\InstallmentPurchases\Tables\InstallmentPurchasesTable;
use App\Models\InstallmentPurchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class InstallmentPurchaseResource extends Resource
{
    protected static ?string $model = InstallmentPurchase::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    public static function getNavigationGroup(): ?string
    {
        return __('Finance');
    }

    protected static ?int $navigationSort = 3;

    protected static ?string $tenantOwnershipRelationshipName = 'wallet';

    public static function getModelLabel(): string
    {
        return __('Installment Purchase');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Installment Purchases');
    }

    public static function form(Schema $schema): Schema
    {
        return InstallmentPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstallmentPurchasesTable::configure($table);
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
            'index' => ListInstallmentPurchases::route('/'),
            'create' => CreateInstallmentPurchase::route('/create'),
            'edit' => EditInstallmentPurchase::route('/{record}/edit'),
        ];
    }
}
