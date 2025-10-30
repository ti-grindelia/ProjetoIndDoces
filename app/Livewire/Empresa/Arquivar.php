<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Arquivar extends Component
{
    public Empresa $empresa;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.empresa.arquivar');
    }

    #[On('empresa::arquivar')]
    public function confirmarArquivamento(int $id): void
    {
        $this->empresa = Empresa::findOrFail($id);
        $this->modal = true;
    }

    public function arquivar(): void
    {
        $this->empresa->Ativo = false;
        $this->empresa->update();

        $this->modal = false;
        $this->dispatch('empresa::recarregar')->to('empresa.index');
    }
}
