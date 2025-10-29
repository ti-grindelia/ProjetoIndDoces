<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
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

    #[On('empresa::recarregar')]
    public function render(): View
    {
        return view('livewire.empresa.index');
    }

    public function mount(): void
    {
        $this->ordenarPelaColuna = 'EmpresaID';
    }

    public function tabelaCabecalho(): array
    {
        return [
            Cabecalho::make('CNPJ', 'CNPJ'),
            Cabecalho::make('RazaoSocial', 'Nome'),
            Cabecalho::make('Telefone', 'Telefone'),
            Cabecalho::make('Email', 'Email'),
            Cabecalho::make('EnderecoCompleto', 'Endereco')
        ];
    }

    public function query(): Builder
    {
        return Empresa::query()
            ->when(
                $this->pesquisaInativos,
                fn(Builder $q) => $q->where('Ativo', false),
                fn(Builder $q) => $q->where('Ativo', true));
    }

    public function colunasPesquisa(): array
    {
        return ['CNPJ', 'RazaoSocial', 'Telefone'];
    }
}
