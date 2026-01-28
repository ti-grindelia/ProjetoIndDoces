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
            $normalizado = $this->normalizarProduto($produto, $quantidade);
            $descricaoBase = $normalizado['descricao_base'];
            $quantidadeFinal = $normalizado['quantidade'];

            $produtoBase = $produtos->first(
                fn($p) => strtoupper(trim($p->Descricao)) === strtoupper(trim($descricaoBase))
            ) ?? $produto;

            $materiasProduto = $this->calcularMateriasProduto($produtoBase, $quantidadeFinal);

            foreach ($materiasProduto as $mp) {
                $this->somarMateriaTotalObjeto($mp);
            }

            $chave = $produtoBase->ProdutoID;

            if (isset($this->produtosProcessados[$chave])) {
                $this->produtosProcessados[$chave]['Quantidade'] += $quantidadeFinal;
            } else {
                $this->produtosProcessados[$chave] =
                    $this->montarProdutoProcessado($produtoBase, $quantidadeFinal);
            }
        }

        foreach ($this->produtosProcessados as $produto) {
            if ($produto['Industria'] == 1) {
                $this->produtosIndustriaDoces[] = $produto;
            } elseif ($produto['Industria'] == 2) {
                $this->produtosIndustriaSalgados[] = $produto;
            }
        }

        foreach ($this->materiasTotais as &$mp) {
            $mp['Total'] = round($mp['Total'], 3);
        }
        unset($mp);

        return [
            'produtosProcessados'      => array_values($this->produtosProcessados),
            'produtosIndustriaDoces'  => $this->produtosIndustriaDoces,
            'produtosIndustriaSalgados' => $this->produtosIndustriaSalgados,
            'materiasTotais'          => $this->materiasTotais,
        ];
    }

    private function normalizarProduto($produto, float $quantidade): array
    {
        $descricao = strtoupper(trim($produto['Descricao']));

        if (str_contains($descricao, 'CENTO')) {
            $descricao = trim(str_replace('CENTO', '', $descricao));
        }

        return [
            'descricao_base' => $descricao,
            'quantidade'     => $quantidade
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

    private function somarMateriaTotalObjeto(array $mp): void
    {
        $id = $mp['MateriaPrimaID'];

        if (!isset($this->materiasTotais[$id])) {
            $this->materiasTotais[$id] = $mp;
            return;
        }

        $this->materiasTotais[$id]['Total'] += $mp['Total'];
        $this->materiasTotais[$id]['Total'] = round($this->materiasTotais[$id]['Total'], 3);
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

    private function calcularMateriasProduto($produto, float $quantidade): array
    {
        $rendimento = $produto->RendimentoProducao ?: 1;
        $fator = $quantidade / $rendimento;

        $materias = [];

        foreach ($produto->materiasPrimas as $mp) {
            $quantBase = $mp->pivot->Quantidade * $fator;

            if ($mp->composicoes->isNotEmpty()) {
                $rendimentoComp = $mp->Rendimento ?: 1;

                foreach ($mp->composicoes as $filha) {
                    $total = ($filha->pivot->Quantidade / $rendimentoComp) * $quantBase;
                    $this->somarMateriaProduto($materias, $filha, $total, $filha->pivot->Quantidade);
                }
            } else {
                $this->somarMateriaProduto($materias, $mp, $quantBase, $mp->pivot->Quantidade);
            }
        }

        return array_values($materias);
    }

    private function somarMateriaProduto(array &$materias, $mp, float $total, float $quantOriginal): void
    {
        $id = $mp->MateriaPrimaID;

        if (!isset($materias[$id])) {
            $materias[$id] = $this->criarEntradaMateria($mp, $total, $quantOriginal);
            return;
        }

        $materias[$id]['Total'] += $total;
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
