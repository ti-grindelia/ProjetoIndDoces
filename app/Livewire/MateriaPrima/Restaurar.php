<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Restaurar extends Component
{
    public MateriaPrima $materiaPrima;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.materia-prima.restaurar');
    }

    #[On('materia-prima::restaurar')]
    public function confirmar(int $id): void
    {
        $this->materiaPrima = MateriaPrima::query()->where('Ativo', false)->findOrFail($id);
        $this->modal = true;
    }

    public function restaurar(): void
    {
        $this->materiaPrima->Ativo = true;
        $this->materiaPrima->update();

        $this->modal = false;
        $this->dispatch('materia-prima::recarregar')->to('materia-prima.index');
    }
}
