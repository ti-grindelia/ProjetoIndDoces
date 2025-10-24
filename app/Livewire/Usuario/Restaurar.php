<?php

namespace App\Livewire\Usuario;

use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Restaurar extends Component
{
    public Usuario $usuario;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.usuario.restaurar');
    }

    #[On('usuario::restaurar')]
    public function confirmar(int $id): void
    {
        $this->usuario = Usuario::query()->where('Ativo', false)->findOrFail($id);
        $this->modal = true;
    }

    public function restaurar(): void
    {
        $this->usuario->Ativo = true;
        $this->usuario->update();

        $this->modal = false;
        $this->dispatch('usuario::recarregar')->to('usuario.index');
    }
}
