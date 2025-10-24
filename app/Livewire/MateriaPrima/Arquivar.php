<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Arquivar extends Component
{
    public MateriaPrima $materiaPrima;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.materia-prima.arquivar');
    }

    #[On('materia-prima::arquivar')]
    public function confirmarArquivamento(int $id): void
    {
        $this->materiaPrima = MateriaPrima::findOrFail($id);
        $this->modal = true;
    }

    public function arquivar(): void
    {
        $this->materiaPrima->Ativo = false;
        $this->materiaPrima->update();

        $this->modal = false;
        $this->dispatch('materia-prima::recarregar')->to('materia-prima.index');
    }
}
