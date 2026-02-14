<?php

namespace App\Livewire\Pedido;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Impressoes extends Component
{
    public $modal = false;

    public ?string $impressao = null;

    public ?int $pedidoID = null;

    public function render(): View
    {
        return view('livewire.pedido.impressoes');
    }

    #[On('pedido::impressoes')]
    public function abrir(int $id): void
    {
        $this->pedidoID = $id;

        $this->modal = true;
    }

    public function imprimir(): void
    {
        $url = '';

        if ($this->impressao === 'materiasPrimas') {
            $url = route('materia.pdf', $this->pedidoID);
        } else if ($this->impressao === 'produtosComReceita') {
            $url = route('pedidoReceita.pdf', $this->pedidoID);
        } else if ($this->impressao === 'produtosSemReceita') {
            $url = route('pedidoSemReceita.pdf', $this->pedidoID);
        } else if ($this->impressao === 'produtosSimples') {
            $url = route('pedidoSimples.pdf', $this->pedidoID);
        }

        $this->dispatch('abrirNovaGuia', url: $url);
    }
}
