<?php

namespace App\Livewire\MateriaPrima;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Criar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.materia-prima.criar');
    }

    #[On('materia-prima::criar')]
    public function abrir(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->criar();

        $this->modal = false;
        $this->dispatch('materia-prima::recarregar')->to('materia-prima.index');
    }
}
