<?php

namespace App\Livewire\Usuario;

use App\Models\Usuario;
use App\Support\Table\Cabecalho;
use App\Traits\Livewire\TemTabela;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use TemTabela;

    public bool $pesquisaInativos = false;

    public function render(): View
    {
        return view('livewire.usuario.index');
    }

    public function mount(): void
    {
        $this->ordenarPelaColuna = 'UsuarioID';
    }

    public function tabelaCabecalho(): array
    {
        return [
            Cabecalho::make('UsuarioID', '#'),
            Cabecalho::make('Nome', 'Nome'),
            Cabecalho::make('Usuario', 'Usuário'),
            Cabecalho::make('Email', 'Email'),
        ];
    }

    public function query(): Builder
    {
        return Usuario::query()
            ->when(
                $this->pesquisaInativos,
                fn(Builder $q) => $q->where('Ativo', false),
                fn(Builder $q) => $q->where('Ativo', true));
    }

    public function colunasPesquisa(): array
    {
        return ['Nome', 'Usuario', 'Email'];
    }
}
