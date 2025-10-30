<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Restaurar extends Component
{
    public Empresa $empresa;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.empresa.restaurar');
    }

    #[On('empresa::restaurar')]
    public function confirmar(int $id): void
    {
        $this->empresa = Empresa::query()->where('Ativo', false)->findOrFail($id);
        $this->modal = true;
    }

    public function restaurar(): void
    {
        $this->empresa->Ativo = true;
        $this->empresa->update();

        $this->modal = false;
        $this->dispatch('empresa::recarregar')->to('empresa.index');
    }
}
