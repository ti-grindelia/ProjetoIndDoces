<?php

namespace App\Traits\Livewire;

use App\Support\Table\Cabecalho;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait TemTabela
{
    public ?string $pesquisa = null;

    public string $ordenarDirecao = 'asc';

    public string $ordenarPelaColuna = '';

    public int $porPagina = 15;

    /** @return Cabecalho[] */
    abstract public function tabelaCabecalho(): array;

    abstract public function query(): Builder;

    abstract public function colunasPesquisa(): array;

    #[Computed]
    public function itens(): LengthAwarePaginator
    {
        $query = $this->query();

        $query->pesquisa($this->pesquisa, $this->colunasPesquisa());

        return $query
            ->orderBy($this->ordenarPelaColuna, $this->ordenarDirecao)
            ->paginate($this->porPagina);
    }

    #[Computed]
    public function cabecalhos(): array
    {
        return collect($this->tabelaCabecalho())
            ->map(function (Cabecalho $cabecalho) {
                return [
                    'key'               => $cabecalho->key,
                    'label'             => $cabecalho->label,
                    'ordenarPelaColuna' => $this->ordenarPelaColuna,
                    'ordenarDirecao'    => $this->ordenarDirecao,
                ];
            })->toArray();
    }

    public function ordenarPor(string $coluna, string $direcao): void
    {
        $this->ordenarPelaColuna = $coluna;
        $this->ordenarDirecao    = $direcao;
    }
}
