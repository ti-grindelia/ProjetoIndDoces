<?php

namespace App\Livewire\Usuario;

use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Atualizar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.usuario.atualizar');
    }

    #[On('usuario::atualizar')]
    public function carregar(int $id): void
    {
        $usuario = Usuario::find($id);

        $this->form->setUsuario($usuario);
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->atualizar();

        $this->modal = false;
        $this->dispatch('usuario::recarregar')->to('usuario.index');
    }
}
