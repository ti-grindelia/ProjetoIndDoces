<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Atualizar extends Component
{
    public AtualizarForm $form;

    public bool $modal = false;

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
            ''           => ''
        };
    }

    public function getStatusButtonDisabledProperty(): bool
    {
        return in_array($this->form->status, ['Finalizado', 'Cancelado', '']);
    }
}
