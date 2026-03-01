<?php

namespace App\Filament\Pages;

use App\Models\Session;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use BackedEnum;

class SecuritySettings extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected string $view = 'filament.pages.security-settings';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Session::query()->where('user_id', Auth::id())
            )
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('IP Address')),
                
                Tables\Columns\TextColumn::make('user_agent')
                    ->label(__('Device / Browser'))
                    ->limit(50),

                Tables\Columns\TextColumn::make('last_activity')
                    ->label(__('Last Activity'))
                    ->formatStateUsing(fn ($state) => \Illuminate\Support\Carbon::createFromTimestamp($state)->diffForHumans())
                    ->sortable(),
            ])
            ->actions([
                Action::make('logout')
                    ->label(__('Logout Device'))
                    ->color('danger')
                    ->icon('heroicon-m-x-mark')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete()),
            ]);
    }

    protected function getHeaderActions(): array
    {
        $user = Auth::user();

        return [
            // ACCIÓN: ACTIVAR 2FA
            Action::make('enable2fa')
                ->label(__('Enable 2FA'))
                ->color('success')
                ->icon('heroicon-m-qr-code')
                ->hidden(fn () => $user->two_factor_confirmed_at !== null)
                ->mountUsing(function () use ($user) {
                    // Generamos el secreto si no existe
                    if (!$user->twoFactorAuth()->exists()) {
                        $user->createTwoFactorAuth();
                    }
                })
                ->form([
                    ViewField::make('qr_code')
                        ->view('filament.pages.two-factor-setup'),
                    
                    TextInput::make('code')
                        ->label(__('Verification Code'))
                        ->placeholder('123456')
                        ->length(6)
                        ->required()
                        ->helperText(__('Enter the 6-digit code from your app to confirm.')),
                ])
                ->action(function (array $data) use ($user) {
                    // Validar el código ingresado
                    if ($user->twoFactorAuth()->validate($data['code'])) {
                        $user->twoFactorAuth()->confirm($data['code']);
                        
                        Notification::make()
                            ->title(__('2FA Enabled Successfully'))
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title(__('Invalid Code'))
                            ->body(__('The code provided is incorrect. Please try again.'))
                            ->danger()
                            ->send();
                    }
                }),

            // ACCIÓN: DESACTIVAR 2FA
            Action::make('disable2fa')
                ->label(__('Disable 2FA'))
                ->color('danger')
                ->icon('heroicon-m-lock-open')
                ->visible(fn () => $user->two_factor_confirmed_at !== null)
                ->requiresConfirmation()
                ->action(function () use ($user) {
                    $user->twoFactorAuth()->disable();
                    
                    Notification::make()
                        ->title(__('2FA Disabled'))
                        ->success()
                        ->send();
                }),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Security');
    }

    public function getTitle(): string
    {
        return __('Security Settings');
    }
}
