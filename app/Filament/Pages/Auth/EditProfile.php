<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    /**
     * Personalizar el formulario de perfil para incluir el selector de idioma y formato.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                
                Select::make('locale')
                    ->label(__('Language'))
                    ->options([
                        'es' => 'Español',
                        'en' => 'English',
                    ])
                    ->required()
                    ->native(false),

                Select::make('number_format')
                    ->label(__('Number & Currency Format'))
                    ->options([
                        'comma_dot' => '1,234.56 (MX/US)',
                        'dot_comma' => '1.234,56 (EU)',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }
}
