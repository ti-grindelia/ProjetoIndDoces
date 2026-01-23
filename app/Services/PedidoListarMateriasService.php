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
            return [
                'PedidoItemID' => $item->PedidoItemID,
                'Quantidade'   => round($item->Quantidade, 2),
                'Produto'      => [
                    'ProdutoID' => $item->produto->ProdutoID,
                    'Descricao' => $item->produto->Descricao,
                ],
                'MateriasPrimas' => $this->calcularMateriasProduto(
                    $item->produto,
                    $item->Quantidade
                ),
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

        if (!isset($materias[$id])) {
            $materias[$id] = [
                'MateriaPrimaID' => $id,
                'Descricao'      => $mp->Descricao,
                'Quantidade'     => $quantidade,
                'Unidade'        => $mp->Unidade,
            ];
        } else {
            $materias[$id]['Quantidade'] += $quantidade;
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
