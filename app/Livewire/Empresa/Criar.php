<?php

namespace App\Livewire\Empresa;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Criar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.empresa.criar');
    }

    public function buscarCEP(): void
    {
        $this->form->buscarEndereco();
        $this->form = clone $this->form;
    }

    #[On('empresa::criar')]
    public function abrir(): void
    {
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->criar();

        $this->modal = false;
        $this->dispatch('empresa::recarregar')->to('empresa.index');
    }
}
