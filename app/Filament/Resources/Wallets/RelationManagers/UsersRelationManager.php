<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\Wallets\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Facades\Auth;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->options([
                        'owner' => __('Owner'),
                        'contributor' => __('Contributor'),
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        // Determinar si el usuario logueado es dueño de esta billetera
        $isOwner = $this->getOwnerRecord()->users()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'owner')
            ->exists();

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email')),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('Role'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'success',
                        'contributor' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => __($state)),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->visible($isOwner)
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('role')
                            ->options([
                                'owner' => __('Owner'),
                                'contributor' => __('Contributor'),
                            ])
                            ->default('contributor')
                            ->required(),
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->visible($isOwner),
                DetachAction::make()
                    ->label(__('Remove'))
                    ->visible(fn ($record) => 
                        $isOwner && $record->id !== Auth::id()
                    ),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->visible($isOwner),
                ]),
            ]);
    }
}
