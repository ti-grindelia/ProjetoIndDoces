<?php

namespace App\Livewire\Pedido;

use App\Imports\PedidoImport;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;


class Criar extends Component
{
    use WithFileUploads;

    public int $proximoPedidoID;

    public string $dataHoraAtual;

    public $arquivo = null;

    public array $produtosProcessados = [];

    public array $produtosIndustriaDoces = [];

    public array $produtosIndustriaSalgados = [];

    public function render(): View
    {
        return view('livewire.pedido.criar');
    }

    public function mount(): void
    {
        $ultimoID = Pedido::max('PedidoID');
        $this->proximoPedidoID = ($ultimoID ?? 0) + 1;

        Carbon::setLocale('pt_BR');
        $this->dataHoraAtual = Carbon::now()->translatedFormat('d \d\e F \d\e Y, H:i');
    }

    public function processar(): void
    {
        $this->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $this->produtosProcessados = [];
        $this->produtosIndustriaDoces = [];
        $this->produtosIndustriaSalgados = [];

        $produtos = Produto::all()->keyBy('CodigoAlternativo');

        $linhas = Excel::toArray(new PedidoImport, $this->arquivo)[0];

        foreach ($linhas as $linha) {
            if (!isset($linha[0])) continue;

            $colA = trim($linha[0]);

            if (!is_numeric($colA)) continue;

            $codigo = (string) $colA;

            $colB = $linha[1] ?? null;
            $colC = $linha[2] ?? null;

            $quantidade = null;

            if ($colC && is_numeric(str_replace(',', '.', $colC))) {
                $quantidade = floatval(str_replace(',', '.', $colC));
            } elseif ($colB && is_numeric(str_replace(['.', ','], '', $colB))) {
                $quantidade = floatval(str_replace(',', '.', $colB));
            }

            if (!$quantidade) continue;

            if (!$produtos->has($codigo)) continue;

            $produto = $produtos[$codigo];

            $produtoProcessado = [
                'ProdutoID'  => $produto->ProdutoID,
                'Codigo'     => $produto->CodigoAlternativo,
                'Descricao'  => $produto->Descricao,
                'Quantidade' => $quantidade,
                'Industria'  => $produto->EmpresaID,
            ];

            $this->produtosProcessados[] = $produtoProcessado;

            if ($produto->EmpresaID == 1) {
                $this->produtosIndustriaDoces[] = $produtoProcessado;
            } elseif ($produto->EmpresaID == 2) {
                $this->produtosIndustriaSalgados[] = $produtoProcessado;
            }
        }
    }
}
