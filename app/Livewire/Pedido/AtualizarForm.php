<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use App\Services\PedidoListarMateriasService;

class AtualizarForm extends Form
{
    public ?Pedido $pedido = null;

    public ?int $pedidoID = null;

    public string $empresa = '';

    public string $usuario = '';

    public string $dataInclusao = '';

    public string $status = '';

    public string $statusFormatado = '';

    public float $custoTotal = 0;

    public ?string $alteradoEm = null;

    public ?string $alteradoPor = null;

    public ?string $canceladoEm = null;

    public ?string $canceladoPor = null;

    public array $itens = [];

    public array $materiasPrimas = [];

    public function setPedido(Pedido $pedido): void
    {
        $this->pedido = $pedido;

        $this->pedidoID     = $pedido->PedidoID;
        $this->empresa      = $pedido->empresa->RazaoSocial;
        $this->usuario      = $pedido->usuario->Nome;
        $this->dataInclusao = $pedido->DataInclusao->format('Y-m-d');
        $this->status       = $pedido->Status;
        if ($this->status == 'Producao') {
            $this->statusFormatado = 'Produção';
        } else {
            $this->statusFormatado = $this->status;
        }
        $this->custoTotal   = $pedido->CustoTotal;
        $this->alteradoEm   = $pedido->AlteradoEm?->format('d/m/Y H:i') ?? null;
        $this->alteradoPor  = $pedido->usuarioAlteracao?->Nome ?? null;
        $this->canceladoEm  = $pedido->CanceladoEm?->format('d/m/Y H:i') ?? null;
        $this->canceladoPor = $pedido->usuarioCancelamento?->Nome ?? null;

        $service = new PedidoListarMateriasService();
        $resultado = $service->calcular($pedido);

        $this->itens        = $resultado['itens'];
        $this->materiasPrimas = $resultado['materiasPrimas'];
    }

    public function atualizarStatusParaProducao(): void
    {
        $pedido = Pedido::find($this->pedidoID);
        $pedido->Status = 'Producao';
        $pedido->AlteradoEm = now();
        $pedido->AlteradoPor = auth()->id();
        $pedido->save();

        $this->status = 'Producao';
        $this->statusFormatado = 'Produção';
    }

    public function atualizarStatusParaFinalizado(): void
    {
        $pedido = Pedido::find($this->pedidoID);
        $pedido->Status = 'Finalizado';
        $pedido->AlteradoEm = now();
        $pedido->AlteradoPor = auth()->id();
        $pedido->save();

        $this->status = 'Finalizado';
        $this->statusFormatado = 'Finalizado';
    }
}
