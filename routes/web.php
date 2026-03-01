<?php

use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para aceptar invitaciones
Route::get('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');
