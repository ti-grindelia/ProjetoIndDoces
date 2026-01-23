<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use App\Services\PedidoListarMateriasService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Atualizar extends Component
{
    use Toast;

    public AtualizarForm $form;

    public bool $modal = false;

    public $service = PedidoListarMateriasService::class;

    public function render(): View
    {
        return view('livewire.pedido.atualizar');
    }

    #[On('pedido::visualizar')]
    public function carregar(int $id): void
    {
        $pedido = Pedido::query()
            ->with([
                'empresa:EmpresaID,RazaoSocial',
                'usuario:UsuarioID,Nome',
                'itens.produto.materiasPrimas',
            ])
            ->find($id);

        $this->form->setPedido($pedido);
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function getStatusButtonLabelProperty(): string
    {
        return match ($this->form->status) {
            'Aberto'     => 'Avançar para Produção',
            'Producao'   => 'Finalizar Pedido',
            'Finalizado' => 'Pedido Finalizado',
            'Cancelado'  => 'Pedido Cancelado',
            ''           => ''
        };
    }

    public function getStatusButtonDisabledProperty(): bool
    {
        return in_array($this->form->status, ['Finalizado', 'Cancelado', '']);
    }

    public function proximoStatus(): void
    {
        match ($this->form->status) {
            'Aberto'     => $this->form->atualizarStatusParaProducao(),
            'Producao'   => $this->form->atualizarStatusParaFinalizado(),
            'Finalizado' => null,
        };

        $this->success('Status do pedido atualizado com sucesso');
        $this->dispatch('pedidos::recarregar');
    }
}
