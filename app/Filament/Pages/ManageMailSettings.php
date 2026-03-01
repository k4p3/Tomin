<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use UnitEnum;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class ManageMailSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-mail-settings';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public function mount(): void
    {
        $settings = Setting::whereIn('key', [
            'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address'
        ])->pluck('value', 'key')->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('SMTP Configuration'))
                    ->description(__('Manage your outgoing email server settings.'))
                    ->schema([
                        TextInput::make('mail_host')
                            ->label(__('Host'))
                            ->required(),
                        TextInput::make('mail_port')
                            ->label(__('Port'))
                            ->numeric()
                            ->required(),
                        TextInput::make('mail_username')
                            ->label(__('Username'))
                            ->required(),
                        TextInput::make('mail_password')
                            ->label(__('Password'))
                            ->password()
                            ->required(),
                        TextInput::make('mail_encryption')
                            ->label(__('Encryption'))
                            ->required(),
                        TextInput::make('mail_from_address')
                            ->label(__('From Address'))
                            ->email()
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->action(function () {
                    foreach ($this->data as $key => $value) {
                        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                    }

                    Notification::make()
                        ->title(__('Settings saved successfully'))
                        ->success()
                        ->send();
                }),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Mail Settings');
    }

    public function getTitle(): string
    {
        return __('Mail Settings');
    }
}
