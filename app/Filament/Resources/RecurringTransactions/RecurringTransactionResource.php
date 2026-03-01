<?php

namespace App\Filament\Resources\RecurringTransactions;

use App\Filament\Resources\RecurringTransactions\Pages\CreateRecurringTransaction;
use App\Filament\Resources\RecurringTransactions\Pages\EditRecurringTransaction;
use App\Filament\Resources\RecurringTransactions\Pages\ListRecurringTransactions;
use App\Filament\Resources\RecurringTransactions\Schemas\RecurringTransactionForm;
use App\Filament\Resources\RecurringTransactions\Tables\RecurringTransactionsTable;
use App\Models\RecurringTransaction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class RecurringTransactionResource extends Resource
{
    protected static ?string $model = RecurringTransaction::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-arrow-path';

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    protected static ?int $navigationSort = 3;

    protected static ?string $tenantOwnershipRelationshipName = 'wallet';

    public static function getModelLabel(): string
    {
        return __('Recurring Transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Recurring Transactions');
    }

    public static function form(Schema $schema): Schema
    {
        return RecurringTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecurringTransactionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecurringTransactions::route('/'),
            'create' => CreateRecurringTransaction::route('/create'),
            'edit' => EditRecurringTransaction::route('/{record}/edit'),
        ];
    }
}
