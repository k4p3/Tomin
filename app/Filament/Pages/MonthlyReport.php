<?php

namespace App\Filament\Pages;

use App\Services\FinanceReportingService;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use UnitEnum;
use BackedEnum;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class MonthlyReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected string $view = 'filament.pages.monthly-report';

    public static function getNavigationGroup(): ?string
    {
        return __('Finance');
    }

    protected static ?int $navigationSort = 10;

    public ?array $data = [];
    public ?array $report = null;

    public function mount(): void
    {
        $this->form->fill([
            'month' => now()->format('Y-m'),
        ]);

        $this->generateReport();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('month')
                    ->label(__('Select Month'))
                    ->format('Y-m')
                    ->displayFormat('F Y')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn() => $this->generateReport()),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label(__('Download PDF'))
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->downloadReport()),
        ];
    }

    public function generateReport(): void
    {
        $date = Carbon::parse($this->data['month'] ?? now());
        $service = new FinanceReportingService();
        
        $this->report = $service->getMonthlySummary($date, Filament::getTenant()->id);
    }

    public function downloadReport()
    {
        $date = Carbon::parse($this->data['month'] ?? now());
        $wallet = Filament::getTenant();
        
        $pdf = Pdf::loadView('reports.monthly-summary-pdf', [
            'report' => $this->report,
            'date' => $date->format('F Y'),
            'walletName' => $wallet->name,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Reporte-{$wallet->name}-{$date->format('Y-m')}.pdf"
        );
    }

    public static function getNavigationLabel(): string
    {
        return __('Monthly Report');
    }

    public function getTitle(): string
    {
        return __('Monthly Report');
    }
}
