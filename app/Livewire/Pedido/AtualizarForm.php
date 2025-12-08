<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;

class AtualizarForm extends Form
{
    public ?Pedido $pedido = null;

    public ?int $pedidoID = null;

    public string $empresa = '';

    public string $usuario = '';

    public string $dataInclusao = '';

    public string $status = '';

    public float $custoTotal = 0;

    public ?string $alteradoEm = null;

    public ?int $alteradoPor = null;

    public ?string $canceladoEm = null;

    public ?int $canceladoPor = null;

    public array $itens = [];

    public function setPedido(Pedido $pedido): void
    {
        $this->pedido = $pedido;

        $this->pedidoID     = $pedido->PedidoID;
        $this->empresa      = $pedido->empresa->RazaoSocial;
        $this->usuario      = $pedido->usuario->Nome;
        $this->dataInclusao = $pedido->DataInclusao->format('Y-m-d');
        $this->status       = $pedido->Status;
        $this->custoTotal   = $pedido->CustoTotal;
        $this->alteradoEm   = $pedido->AlteradoEm;
        $this->alteradoPor  = $pedido->AlteradoPor;
        $this->canceladoEm  = $pedido->CanceladoEm;
        $this->canceladoPor = $pedido->CanceladoPor;
        $this->itens        = $pedido->itens->map(function ($item) {
            return [
                'PedidoItemID' => $item->PedidoItemID,
                'Quantidade'   => $item->Quantidade,
                'Produto'      => [
                    'ProdutoID' => $item->produto->ProdutoID,
                    'Descricao' => $item->produto->Descricao
                ],
                'MateriasPrimas' => $item->produto->materiasPrimas->map(function ($mp) {
                    return [
                        'MateriaPrimaID' => $mp->MateriaPrimaID,
                        'Descricao' => $mp->Descricao,
                        'Quantidade' => $mp->pivot->Quantidade,
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
