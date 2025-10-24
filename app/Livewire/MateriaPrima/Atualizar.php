<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Atualizar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.materia-prima.atualizar');
    }

    #[On('materia-prima::atualizar')]
    public function carregar(int $id): void
    {
        $materiaPrima = MateriaPrima::find($id);

        $this->form->setMateriaPrima($materiaPrima);
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->atualizar();

        $this->modal = false;
        $this->dispatch('materia-prima::recarregar')->to('materia-prima.index');
    }
}
