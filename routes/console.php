<?php

use Illuminate\Support\Facades\Schedule;

// Procesar transacciones recurrentes diariamente a la medianoche
Schedule::command('transactions:process-recurring')->daily();

// Procesar mensualidades de MSI diariamente a la medianoche
Schedule::command('msi:process')->daily();

// Recordatorios de transacciones recurrentes (24h antes)
Schedule::command('transactions:remind-recurring')->dailyAt('08:00');

// Recordatorios de pago de tarjetas (3 días antes)
Schedule::command('cards:remind-payments')->dailyAt('09:00');

// Captura de patrimonio neto diaria
Schedule::command('wallets:snapshot')->dailyAt('23:59');
