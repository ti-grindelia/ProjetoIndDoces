<?php

namespace App\Livewire\Usuario;

use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Arquivar extends Component
{
    public Usuario $usuario;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.usuario.arquivar');
    }

    #[On('usuario::arquivar')]
    public function confirmarArquivamento(int $id): void
    {
        $this->usuario = Usuario::findOrFail($id);
        $this->modal = true;
    }

    public function arquivar(): void
    {
        $this->usuario->Ativo = false;
        $this->usuario->update();

        $this->modal = false;
        $this->dispatch('usuario::recarregar')->to('usuario.index');
    }
}
