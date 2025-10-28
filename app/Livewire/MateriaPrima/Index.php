<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use App\Support\Table\Cabecalho;
use App\Traits\Livewire\TemTabela;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use TemTabela;

    public bool $pesquisaInativos = false;

    #[On('materia-prima::recarregar')]
    public function render(): View
    {
        return view('livewire.materia-prima.index');
    }

    public function mount(): void
    {
        $this->ordenarPelaColuna = 'MateriaPrimaID';
    }

    public function tabelaCabecalho(): array
    {
        return [
            Cabecalho::make('MateriaPrimaID', '#'),
            Cabecalho::make('CodigoAlternativo', 'Cod.Alternativo'),
            Cabecalho::make('Nome', 'Nome'),
            Cabecalho::make('Unidade', 'Unidade'),
            Cabecalho::make('Valor', 'Valor'),
            Cabecalho::make('CustoMedio', 'Custo Médio')
        ];
    }

    public function query(): Builder
    {
        return MateriaPrima::query()
            ->when(
                $this->pesquisaInativos,
                fn(Builder $q) => $q->where('Ativo', false),
                fn(Builder $q) => $q->where('Ativo', true));
    }

    public function colunasPesquisa(): array
    {
        return ['CodigoAlternativo', 'Nome'];
    }
}
