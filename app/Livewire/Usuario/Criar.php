<?php

namespace App\Livewire\Usuario;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Criar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.usuario.criar');
    }

    #[On('usuario::criar')]
    public function abrir(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->criar();

        $this->modal = false;
        $this->dispatch('usuario::recarregar')->to('usuario.index');
    }
}
