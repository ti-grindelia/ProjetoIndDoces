<?php

use App\Livewire\Autenticacao\Login;
use App\Livewire\Autenticacao\Registro;
use App\Livewire\Usuario;
use App\Livewire\Empresa;
use App\Livewire\Produto;
use App\Livewire\MateriaPrima;
use App\Livewire\Welcome;
use App\Livewire\Pedido;

Route::get('/login', Login::class)->name('login');
Route::get('/registro', Registro::class)->name('auth.registro');
Route::get('/logout', fn() => auth()->logout())->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    Route::get('/usuario', Usuario\Index::class)->name('usuario');
    Route::get('/empresa', Empresa\Index::class)->name('empresa');
    Route::get('/materia-prima', MateriaPrima\Index::class)->name('materia-prima');
    Route::get('/produto', Produto\Index::class)->name('produto');

    Route::get('/pedidos', Pedido\Index::class)->name('pedidos');
    Route::get('/novoPedido', Pedido\Criar::class)->name('novoPedido');
});
