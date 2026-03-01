<?php

namespace App\Filament\Resources\Merchants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MerchantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Select::make('default_category_id')
                    ->label(__('Category'))
                    ->relationship('defaultCategory', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
