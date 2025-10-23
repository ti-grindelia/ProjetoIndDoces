<?php

use App\Livewire\Autenticacao\Login;
use App\Livewire\Autenticacao\Registro;
use App\Livewire\Welcome;

Route::get('/login', Login::class)->name('login');
Route::get('/registro', Registro::class)->name('auth.registro');
Route::get('/logout', fn() => auth()->logout())->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
});
