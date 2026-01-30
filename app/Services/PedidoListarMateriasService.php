<?php

namespace App\Services;

use App\Models\Pedido;

class PedidoListarMateriasService
{
    public function calcular(Pedido $pedido): array
    {
        $pedido->load([
            'itens.produto.materiasPrimas.composicoes'
        ]);

        $itens = $this->montarItensPedido($pedido);

        return [
            'itens' => $itens,
            'materiasPrimas' => $this->somarMateriasPrimasTotais($itens),
        ];
    }

    private function montarItensPedido(Pedido $pedido): array
    {
        return $pedido->itens->map(function ($item) {
            $quantidade = (float) $item->Quantidade;
            $produto = $item->produto;

            $custoMateriaPrima = $produto->CustoMateriaPrima ?? 0;
            $custoIndustrializacao = $produto->CustoIndustrializacao ?? 0;
            $custoTotalUnitario = $custoMateriaPrima + $custoIndustrializacao;

            $mvaPercentual = ((float) ($produto->MVAPercentual ?? 0)) / 100;
            $icmsPercentual = ((float) ($produto->ICMSPercentual ?? 0)) / 100;

            $valorMva = round($quantidade * $custoTotalUnitario * $mvaPercentual, 2);
            $valorIcms = round($valorMva * $icmsPercentual, 2);

            $materias = $this->calcularMateriasProduto($produto, $quantidade);

            $custoProduto = collect($materias)->sum('CustoTotal');

            return [
                'PedidoItemID' => $item->PedidoItemID,
                'Quantidade'   => round($quantidade, 2),
                'Produto'      => [
                    'ProdutoID' => $produto->ProdutoID,
                    'CodigoAlternativo' => $produto->CodigoAlternativo,
                    'Descricao' => $produto->Descricao,
                    'CustoMateriaPrima' => $custoMateriaPrima,
                    'CustoIndustrializacao' => $custoIndustrializacao,
                    'CustoTotal' => $custoTotalUnitario,
                    'MVAPercentual' => $produto->MVAPercentual ?? 0,
                    'ICMSPercentual' => $produto->ICMSPercentual ?? 0,
                    'ValorMVA'  => $valorMva,
                    'ValorICMS' => $valorIcms,
                ],
                'MateriasPrimas' => $materias,
                'CustoTotal'    => round($custoProduto, 2),
                'CustoUnitario' => round($custoProduto / max($item->Quantidade, 1), 2)
            ];
        })->toArray();
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
                    $this->somarMateria($materias, $filha, $total);
                }
            } else {
                $this->somarMateria($materias, $mp, $quantBase);
            }
        }

        foreach ($materias as &$mp) {
            $mp['Quantidade'] = round($mp['Quantidade'], 3);
        }

        return array_values($materias);
    }

    private function somarMateria(array &$materias, $mp, float $quantidade): void
    {
        $id = $mp->MateriaPrimaID;
        $preco = (float) ($mp->PrecoCompra ?? 0);

        if (!isset($materias[$id])) {
            $materias[$id] = [
                'MateriaPrimaID' => $id,
                'CodigoAlternativo' => $mp->CodigoAlternativo,
                'Descricao'      => $mp->Descricao,
                'Quantidade'     => $quantidade,
                'Unidade'        => $mp->Unidade,
                'PrecoCompra'    => $preco,
                'CustoTotal'     => $preco * $quantidade,
            ];
        } else {
            $materias[$id]['Quantidade'] += $quantidade;
            $materias[$id]['CustoTotal'] += $preco * $quantidade;
        }
    }

    private function somarMateriasPrimasTotais(array $itens): array
    {
        $totais = [];

        foreach ($itens as $item) {
            foreach ($item['MateriasPrimas'] as $mp) {
                $id = $mp['MateriaPrimaID'];

                if (!isset($totais[$id])) {
                    $totais[$id] = $mp;
                } else {
                    $totais[$id]['Quantidade'] += $mp['Quantidade'];
                }
            }
        }

        foreach ($totais as &$mp) {
            $mp['Quantidade'] = round($mp['Quantidade'], 3);
        }

        return array_values($totais);
    }
}
