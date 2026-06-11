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
        $url = $this->getRoute('pdf');

        $this->dispatch('abrirNovaGuia', url: $url);
    }

    public function exportar(): void
    {
        $url = $this->getRoute('excel');

        $this->dispatch('abrirNovaGuia', url: $url);
    }

    private function getRoute(string $formato): string
    {
        return match ($this->impressao) {
            'materiasPrimas' => route("materia.$formato", $this->pedidoID),
            'produtosComReceita' => route("pedidoReceita.$formato", $this->pedidoID),
            'produtosSemReceita' => route("pedidoSemReceita.$formato", $this->pedidoID),
            'produtosSimples' => route("pedidoSimples.$formato", $this->pedidoID),
            default => '',
        };
    }
}
