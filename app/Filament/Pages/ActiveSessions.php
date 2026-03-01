<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use BackedEnum;

class ActiveSessions extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected string $view = 'filament.pages.active-sessions';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DB::table('sessions')->where('user_id', Auth::id())
            )
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('IP Address')),
                
                Tables\Columns\TextColumn::make('user_agent')
                    ->label(__('Device / Browser'))
                    ->limit(50),

                Tables\Columns\TextColumn::make('last_activity')
                    ->label(__('Last Activity'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('logout')
                    ->label(__('Logout Device'))
                    ->color('danger')
                    ->icon('heroicon-m-x-mark')
                    ->requiresConfirmation()
                    ->action(fn ($record) => DB::table('sessions')->where('id', $record->id)->delete()),
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return __('Active Sessions');
    }

    public function getTitle(): string
    {
        return __('Active Sessions');
    }
}
