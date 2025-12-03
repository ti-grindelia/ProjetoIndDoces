<?php

namespace App\Livewire\Pedido;

use App\Imports\PedidoImport;
use App\Models\Pedido;
use App\Models\PedidoItens;
use App\Models\PedidoMateriasPrimas;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

    public array $materiasTotais = [];

    public float $custoDoces = 0;

    public float $custoSalgados = 0;

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
        $this->materiasTotais = [];

        $produtos = Produto::with('materiasPrimas')
            ->orderBy('ProdutoID')
            ->get()
            ->keyBy('CodigoAlternativo');

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
            $rendimento = $produto->RendimentoProducao ?: 1;
            $fator = $quantidade / $rendimento;

            foreach ($produto->materiasPrimas as $mp) {
                $quantBase = $mp->pivot->Quantidade * $fator;

                if ($mp->composicoes()->exists()) {
                    foreach ($mp->composicoes as $filha) {
                        $qtdFilha = $quantBase * $filha->pivot->Quantidade;
                        $this->somarMateriaTotal($filha, $qtdFilha);
                    }
                } else {
                    $this->somarMateriaTotal($mp, $quantBase);
                }
            }

            $produtoProcessado = [
                'ProdutoID'  => $produto->ProdutoID,
                'Codigo'     => $produto->CodigoAlternativo,
                'Descricao'  => $produto->Descricao,
                'Quantidade' => $quantidade,
                'Industria'  => $produto->EmpresaID,
                'MateriasPrimas' => $this->calcularMateriasProduto($produto, $quantidade),
            ];

            $this->produtosProcessados[] = $produtoProcessado;

            if ($produto->EmpresaID == 1) {
                $this->produtosIndustriaDoces[] = $produtoProcessado;
            } elseif ($produto->EmpresaID == 2) {
                $this->produtosIndustriaSalgados[] = $produtoProcessado;
            }
        }
    }

    private function somarMateriaTotal($mp, $quantidade): void
    {
        $id = $mp->MateriaPrimaID;

        if (!isset($this->materiasTotais[$id])) {
            $this->materiasTotais[$id] = [
                'CodigoAlternativo' => $mp->CodigoAlternativo,
                'MateriaPrimaID' => $id,
                'Descricao' => $mp->Descricao,
                'Unidade' => $mp->Unidade,
                'Total' => 0,
            ];
        }

        $this->materiasTotais[$id]['Total'] += $quantidade;
        $this->materiasTotais[$id]['Total'] = round($this->materiasTotais[$id]['Total'], 3);
    }

    private function calcularMateriasProduto($produto, $quantidade): array
    {
        $rendimento = $produto->RendimentoProducao ?: 1;
        $fator = $quantidade / $rendimento;

        $materias = [];

        foreach ($produto->materiasPrimas as $mp) {
            $quantBase = $mp->pivot->Quantidade * $fator;

            if ($mp->composicoes()->exists()) {
                foreach ($mp->composicoes as $filha) {

                    $totalFilha = $quantBase * $filha->pivot->Quantidade;

                    $precoFilha = $filha->PrecoCompra ?? 0;
                    $custoFilha = $totalFilha * $precoFilha;

                    $materias[] = [
                        'MateriaPrimaID' => $filha->MateriaPrimaID,
                        'CodigoAlternativo' => $filha->CodigoAlternativo,
                        'Descricao' => $filha->Descricao,
                        'Unidade' => $filha->Unidade,
                        'QuantidadeBase' => $filha->pivot->Quantidade,
                        'Total' => $totalFilha,
                        'PrecoCompra' => $precoFilha,
                        'CustoTotal' => round($custoFilha, 2)
                    ];
                }
            }

            else {
                $precoMP = $mp->PrecoCompra ?? 0;
                $custoMP = $totalFilha ?? 0;

                $custoMP = $quantBase * $precoMP;

                $materias[] = [
                    'MateriaPrimaID' => $mp->MateriaPrimaID,
                    'CodigoAlternativo' => $mp->CodigoAlternativo,
                    'Descricao' => $mp->Descricao,
                    'Unidade' => $mp->Unidade,
                    'QuantidadeBase' => $mp->pivot->Quantidade,
                    'Total' => $quantBase,
                    'PrecoCompra' => $precoMP,
                    'CustoTotal' => round($custoMP, 2)
                ];
            }
        }

        return $materias;
    }

    public function concluirPedido(): void
    {
        DB::beginTransaction();

        try {

            $this->custoDoces = 0;
            $this->custoSalgados = 0;

            foreach ($this->produtosIndustriaDoces as $produto) {
                $this->custoDoces += array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));
            }

            foreach ($this->produtosIndustriaSalgados as $produto) {
                $this->custoSalgados += array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));
            }

            // FUNÇÃO PARA CRIAR PEDIDOS
            $criarPedido = function ($empresaID, $produtos, $custoTotal) {

                if ($custoTotal <= 0 || empty($produtos)) {
                    return null;
                }

                $pedido = Pedido::create([
                    'EmpresaID' => $empresaID,
                    'UsuarioID' => auth()->id(),
                    'DataInclusao' => now(),
                    'Status' => 'Aberto',
                    'CustoTotal' => $custoTotal
                ]);

                $itensInsert = [];
                $materiasInsert = [];

                foreach ($produtos as $produto) {

                    $custoProduto = array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));

                    $itensInsert[] = [
                        'PedidoID' => $pedido->PedidoID,
                        'ProdutoID' => $produto['ProdutoID'],
                        'Quantidade' => $produto['Quantidade'],
                        'CustoTotal' => $custoProduto
                    ];

                    if (!empty($produto['MateriasPrimas'])) {

                        foreach ($produto['MateriasPrimas'] as $mp) {
                            if (empty($mp['MateriaPrimaID'])) {
                                continue;
                            }

                            $materiasInsert[] = [
                                'PedidoID' => $pedido->PedidoID,
                                'MateriaPrimaID' => $mp['MateriaPrimaID'],
                                'Quantidade' => $mp['Total'],
                                'CustoTotal' => $mp['CustoTotal']
                            ];
                        }
                    }
                }

                if (!empty($itensInsert)) {
                    PedidoItens::insert($itensInsert);
                }

                if (!empty($materiasInsert)) {
                    PedidoMateriasPrimas::insert($materiasInsert);
                }

                return $pedido;
            };

            $criarPedido(1, $this->produtosIndustriaDoces, $this->custoDoces);
            $criarPedido(2, $this->produtosIndustriaSalgados, $this->custoSalgados);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
