<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Atualizar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.empresa.atualizar');
    }

    public function buscarCEP(): void
    {
        $this->form->buscarEndereco();
        $this->form = clone $this->form;
    }

    #[On('empresa::atualizar')]
    public function carregar(int $id): void
    {
        $empresa = Empresa::find($id);

        $this->form->setEmpresa($empresa);
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->atualizar();

        $this->modal = false;
        $this->dispatch('empresa::recarregar')->to('empresa.index');
    }
}
