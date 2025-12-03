<?php

namespace App\Livewire\Pedido;

use App\Models\Empresa;
use App\Models\Pedido;
use App\Support\Table\Cabecalho;
use App\Traits\Livewire\TemTabela;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination;
    use TemTabela;

    public $empresas = [];

    public int $empresaFiltro = 0;

    public string $statusFiltro = 'Abertos';

    public ?string $dataFiltro = null;

    public bool $pesquisaInativos = false;

    #[On('pedidos::recarregar')]
    public function render(): View
    {
        return view('livewire.pedido.index');
    }

    public function mount(): void
    {
        $this->ordenarPelaColuna = 'PedidoID';
        $this->empresas = Empresa::query()
            ->where('Ativo', true)
            ->orderBy('RazaoSocial')
            ->get(['EmpresaID', 'RazaoSocial'])
            ->map(fn ($e) => ['id' => $e->EmpresaID, 'name' => $e->RazaoSocial])
            ->toArray();
        $this->dataFiltro = now()->format('Y-m-d');
    }

    public function tabelaCabecalho(): array
    {
        return [
            Cabecalho::make('PedidoID', '#'),
            Cabecalho::make('empresa.RazaoSocial', 'Empresa'),
            Cabecalho::make('Status', 'Status'),
            Cabecalho::make('data_formatada', 'Data'),
            Cabecalho::make('custo_formatado', 'Custo (R$)'),
        ];
    }

    public function query(): Builder
    {
        return Pedido::query()
            ->with('empresa')
            ->when($this->empresaFiltro && $this->empresaFiltro != 0, function (Builder $q) {
                $q->where('EmpresaID', $this->empresaFiltro);
            })
            ->when($this->statusFiltro !== 'Todos', function (Builder $q) {
                if ($this->statusFiltro === 'Abertos') {
                    $q->where('Status', 'Aberto');
                } elseif ($this->statusFiltro === 'Producao') {
                    $q->where('Status', 'Producao');
                } elseif ($this->statusFiltro === 'Finalizados') {
                    $q->where('Status', 'Finalizado');
                } elseif ($this->statusFiltro === 'Cancelados') {
                    $q->where('Status', 'Cancelado');
                }
            })
            ->when($this->dataFiltro, function (Builder $q) {
                $q->whereDate('DataInclusao', $this->dataFiltro);
            });
    }

    public function colunasPesquisa(): array
    {
        return ['Pedido', 'EmpresaID', 'Data'];
    }
}
