<?php

namespace App\Livewire\Produto;

use App\Models\Produto;
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
        return view('livewire.produto.index');
    }

    public function mount(): void
    {
        $this->ordenarPelaColuna = 'ProdutoID';
    }

    public function tabelaCabecalho(): array
    {
        return [
            Cabecalho::make('CodigoAlternativo', '#'),
            Cabecalho::make('Descricao', 'Descrição'),
            Cabecalho::make('Categoria', 'Categoria'),
            Cabecalho::make('Preco', 'Preço (R$)'),
        ];
    }

    public function query(): Builder
    {
        return Produto::query()
            ->when(
                $this->pesquisaInativos,
                fn(Builder $q) => $q->where('Ativo', false),
                fn(Builder $q) => $q->where('Ativo', true));
    }

    public function colunasPesquisa(): array
    {
        return ['CodigoAlternativo', 'Descricao', 'Categoria'];
    }
}
