<?php

namespace App\Livewire\Autenticacao;

use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Registro extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $nome = null;

    #[Rule(['required', 'max:255', 'unique:Usuarios,Usuario'])]
    public ?string $usuario = null;

    #[Rule(['nullable', 'email', 'max:255', 'unique:Usuarios,Email'])]
    public ?string $email = null;

    #[Rule(['required'])]
    public ?string $senha = null;

    public bool $ativo = true;

    #[Layout('components.layouts.convidado')]
    public function render(): View
    {
        return view('livewire.autenticacao.registro');
    }

    public function registrar(): void
    {
        $this->validate();

        $user = Usuario::create([
            'Nome'    => $this->nome,
            'Usuario' => $this->usuario,
            'Email'   => $this->email,
            'Senha'   => $this->senha,
            'Ativo'   => $this->ativo,
        ]);

        auth()->login($user);

        $this->redirect(route('dashboard'));
    }
}
