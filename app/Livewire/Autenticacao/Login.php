<?php

namespace App\Livewire\Autenticacao;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public ?string $usuario = null;
    public ?string $senha = null;

    #[Layout('components.layouts.convidado')]
    public function render(): View
    {
        return view('livewire.autenticacao.login');
    }

    public function tentarLogar(): void
    {
        if (!Auth::attempt(['Usuario' => $this->usuario, 'password' => $this->senha])) {

            $this->addError('credenciaisInvalidas', trans('auth.failed'));

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
