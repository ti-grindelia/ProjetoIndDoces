<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use App\Models\PedidoItens;
use App\Models\PedidoMateriasPrimas;
use Illuminate\Support\Facades\DB;
use Livewire\Form as BaseForm;
use Throwable;

class Form extends BaseForm
{
    public array $produtosIndustriaDoces = [];

    public array $produtosIndustriaSalgados = [];

    public float $custoDoces = 0;

    public float $custoSalgados = 0;

    /**
     * @throws Throwable
     */
    public function concluir(): void
    {
        DB::beginTransaction();

        try {
            $this->calcularCustos();

            $this->criarPedido(
                empresaID: 1,
                produtos: $this->produtosIndustriaDoces,
                custoTotal: $this->custoDoces,
            );
            $this->criarPedido(
                empresaID: 2,
                produtos: $this->produtosIndustriaSalgados,
                custoTotal: $this->custoSalgados,
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calcularCustos(): void
    {
        $this->custoDoces = 0;
        $this->custoSalgados = 0;

        foreach ($this->produtosIndustriaDoces as $produto) {
            $this->custoDoces += array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));
        }
        foreach ($this->produtosIndustriaSalgados as $produto) {
            $this->custoSalgados += array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));
        }
    }

    public function criarPedido(int $empresaID, array $produtos, float $custoTotal): ?Pedido
    {
        if ($custoTotal <= 0 || empty($produtos)) {
            return null;
        }

        $pedido = Pedido::query()->create([
            'EmpresaID' => $empresaID,
            'UsuarioID' => auth()->id(),
            'DataInclusao' => now(),
            'Status' => 'Aberto',
            'CustoTotal' => $custoTotal
        ]);

        $this->inserirItens($pedido->PedidoID, $produtos);

        return $pedido;
    }

    private function inserirItens(int $pedidoID, array $produtos): void
    {
        $itens = [];
        $materias = [];

        foreach ($produtos as $produto) {

            $custoProduto = array_sum(array_column($produto['MateriasPrimas'] ?? [], 'CustoTotal'));

            $itens[] = [
                'PedidoID' => $pedidoID,
                'ProdutoID' => $produto['ProdutoID'],
                'Quantidade' => $produto['Quantidade'],
                'CustoTotal' => $custoProduto
            ];

            foreach ($produto['MateriasPrimas'] as $mp) {

                if (empty($mp['MateriaPrimaID'])) {
                    continue;
                }

                $materias[] = [
                    'PedidoID' => $pedidoID,
                    'MateriaPrimaID' => $mp['MateriaPrimaID'],
                    'Quantidade' => $mp['Quantidade'],
                    'CustoTotal' => $mp['CustoTotal']
                ];
            }
        }

        if (!empty($itens)) {
            PedidoItens::query()->insert($itens);
        }

        if (!empty($materias)) {
            PedidoMateriasPrimas::query()->insert($materias);
        }
    }
}
