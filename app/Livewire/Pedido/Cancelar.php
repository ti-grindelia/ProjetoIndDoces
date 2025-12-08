<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Cancelar extends Component
{
    use Toast;

    public ?Pedido $pedido = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.pedido.cancelar');
    }

    #[On('pedido::cancelar')]
    public function abrirConfirmacao(int $id): void
    {
        $this->pedido = Pedido::select('PedidoID', 'EmpresaID')->with('empresa')->find($id);
        $this->modal = true;
    }

    public function cancelar(): void
    {
        $this->pedido->Status = 'Cancelado';
        $this->pedido->CanceladoEm = now();
        $this->pedido->CanceladoPor = auth()->id();
        $this->pedido->save();

        $this->reset();
        $this->dispatch('pedidos::recarregar')->to('pedido.index');
        $this->success('Pedido cancelado com sucesso');
    }
}
