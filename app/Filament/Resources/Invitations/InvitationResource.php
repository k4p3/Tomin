<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\Invitations;

use App\Filament\Resources\Invitations\Pages\CreateInvitation;
use App\Filament\Resources\Invitations\Pages\EditInvitation;
use App\Filament\Resources\Invitations\Pages\ListInvitations;
use App\Filament\Resources\Invitations\Schemas\InvitationForm;
use App\Filament\Resources\Invitations\Tables\InvitationsTable;
use App\Models\Invitation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class InvitationResource extends Resource
{
    protected static ?string $model = Invitation::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-plus';

    public static function getNavigationGroup(): ?string
    {
        return __('Access');
    }

    protected static ?string $tenantOwnershipRelationshipName = 'wallet';

    public static function getModelLabel(): string
    {
        return __('Invitation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Invitations');
    }

    /**
     * Añadir contador de invitaciones pendientes a la barra lateral.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return InvitationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvitationsTable::configure($table);
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
            'index' => ListInvitations::route('/'),
            'create' => CreateInvitation::route('/create'),
            'edit' => EditInvitation::route('/{record}/edit'),
        ];
    }
}
