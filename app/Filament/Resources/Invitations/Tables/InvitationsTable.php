<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\Invitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class InvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),

                TextColumn::make('expires_at')
                    ->label(__('Expires at'))
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->hasExpired() ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('copyLink')
                    ->label(__('Copy Link'))
                    ->icon('heroicon-m-clipboard')
                    ->color('success')
                    ->action(function ($record) {
                        $url = route('invitation.accept', ['token' => $record->token]);
                        
                        Notification::make()
                            ->title('Enlace de invitación generado')
                            ->body("Copia este enlace: $url")
                            ->persistent()
                            ->send();
                    }),
                ViewAction::make(),
                DeleteBulkAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
