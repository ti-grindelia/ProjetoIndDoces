<?php

namespace App\Livewire\Autenticacao;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = null;
    public ?string $senha = null;

    #[Layout('components.layouts.convidado')]
    public function render(): View
    {
        return view('livewire.autenticacao.login');
    }

    public function tentarLogar(): void
    {
        if (!Auth::attempt(['email' => $this->email, 'senha' => $this->senha])) {

            $this->addError('credenciaisInvalidas', 'As credenciais não correspondem aos nossos registros');

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
