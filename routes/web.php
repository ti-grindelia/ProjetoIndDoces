<?php

use App\Livewire\Autenticacao\Login;
use App\Livewire\Welcome;

Route::get('/login', Login::class)->name('login');

Route::get('/', fn() => 'oi')->name('dashboard');
