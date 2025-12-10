<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PedidoImport;

class PedidoProcessamentoService
{
    public array $produtosProcessados = [];
    public array $produtosIndustriaDoces = [];
    public array $produtosIndustriaSalgados = [];
    public array $materiasTotais = [];

    public function processar($arquivo): array
    {
        $this->resetarListas();

        $produtos = $this->buscarProdutos();
        $linhas = $this->carregarLinhasExcel($arquivo);

        foreach ($linhas as $linha) {
            $codigo = $this->extrairCodigo($linha);
            if (!$codigo || !$produtos->has($codigo)) continue;

            $quantidade = $this->extrairQuantidade($linha);
            if (!$quantidade) continue;

            $produto = $produtos[$codigo];

            $this->processarMateriasTotais($produto, $quantidade);

            $produtoProcessado = $this->montarProdutoProcessado($produto, $quantidade);

            $this->registrarProdutoPorIndustria($produtoProcessado, $produto->EmpresaID);
        }

        return [
            'produtosProcessados'      => $this->produtosProcessados,
            'produtosIndustriaDoces'  => $this->produtosIndustriaDoces,
            'produtosIndustriaSalgados' => $this->produtosIndustriaSalgados,
            'materiasTotais'          => $this->materiasTotais,
        ];
    }

    private function resetarListas(): void
    {
        $this->produtosProcessados = [];
        $this->produtosIndustriaDoces = [];
        $this->produtosIndustriaSalgados = [];
        $this->materiasTotais = [];
    }

    private function buscarProdutos(): Collection
    {
        return Produto::with('materiasPrimas.composicoes')
            ->orderBy('ProdutoID')
            ->get()
            ->keyBy('CodigoAlternativo');
    }

    private function carregarLinhasExcel($arquivo): array
    {
        return Excel::toArray(new PedidoImport, $arquivo)[0];
    }

    private function extrairCodigo($linha): ?string
    {
        if (!isset($linha[0])) return null;
        if (!is_numeric($linha[0])) return null;

        return trim((string) $linha[0]);
    }

    private function extrairQuantidade($linha): ?float
    {
        $b = $linha[1] ?? null;
        $c = $linha[2] ?? null;

        if ($c && is_numeric(str_replace(',', '.', $c))) {
            return floatval(str_replace(',', '.', $c));
        }

        if ($b && is_numeric(str_replace(['.', ','], '', $b))) {
            return floatval(str_replace(',', '.', $b));
        }

        return null;
    }

    private function processarMateriasTotais($produto, float $quantidade): void
    {
        foreach ($produto->materiasPrimas as $mp) {
            $quantBase = $mp->pivot->Quantidade * $quantidade;

            if ($mp->composicoes->count()) {

                $rendimentoComp = $mp->Rendimento ?: 1;

                foreach ($mp->composicoes as $filha) {
                    $qtdOriginalFilha = $filha->pivot->Quantidade;

                    $qtdFilha = ($qtdOriginalFilha / $rendimentoComp) * $quantBase;

                    $this->somarMateriaTotal($filha, $qtdFilha);
                }

            } else {
                $this->somarMateriaTotal($mp, $quantBase);
            }
        }
    }

    private function montarProdutoProcessado($produto, float $quantidade): array
    {
        return [
            'ProdutoID'  => $produto->ProdutoID,
            'Codigo'     => $produto->CodigoAlternativo,
            'Descricao'  => $produto->Descricao,
            'Quantidade' => $quantidade,
            'Industria'  => $produto->EmpresaID,
            'MateriasPrimas' => $this->calcularMateriasProduto($produto, $quantidade),
        ];
    }

    private function registrarProdutoPorIndustria(array $produto, int $empresaId): void
    {
        $this->produtosProcessados[] = $produto;

        if ($empresaId == 1) {
            $this->produtosIndustriaDoces[] = $produto;
        } elseif ($empresaId == 2) {
            $this->produtosIndustriaSalgados[] = $produto;
        }
    }

    private function somarMateriaTotal($mp, float $quantidade): void
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

    private function calcularMateriasProduto($produto, float $quantidade): array
    {
        $rendimento = $produto->RendimentoProducao ?: 1;
        $fator = $quantidade / $rendimento;
        $materias = [];

        foreach ($produto->materiasPrimas as $mp) {
            $quantBase = $mp->pivot->Quantidade * $fator;

            if ($mp->composicoes->count()) {
                $rendimentoComp = $mp->Rendimento ?: 1;

                foreach ($mp->composicoes as $filha) {
                    $qtdOriginalFilha = $filha->pivot->Quantidade;

                    $total = ($qtdOriginalFilha / $rendimentoComp) * $quantBase;
                    $materias[] = $this->criarEntradaMateria($filha, $total, $filha->pivot->Quantidade);
                }
            } else {
                $materias[] = $this->criarEntradaMateria($mp, $quantBase, $mp->pivot->Quantidade);
            }
        }

        return $materias;
    }

    private function criarEntradaMateria($mp, float $total, float $quantBase): array
    {
        $preco = $mp->PrecoCompra ?? 0;

        return [
            'MateriaPrimaID' => $mp->MateriaPrimaID,
            'CodigoAlternativo' => $mp->CodigoAlternativo,
            'Descricao' => $mp->Descricao,
            'Unidade' => $mp->Unidade,
            'QuantidadeBase' => $quantBase,
            'Total' => $total,
            'PrecoCompra' => $preco,
            'CustoTotal' => round($total * $preco, 2),
        ];
    }
}
