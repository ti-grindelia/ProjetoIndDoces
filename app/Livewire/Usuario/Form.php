<?php

namespace App\Livewire\Usuario;

use App\Models\Usuario;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Usuario $usuario = null;

    public string $nome = '';

    public string $usuarioNome = '';

    public string $email = '';

    public string $senha = '';

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'nome'        => ['required', 'min:3', 'max:255'],
            'usuarioNome' => ['required', 'min:3', 'max:255', 'unique:Usuarios,Usuario'],
            'email'       => ['nullable', 'email', 'unique:Usuarios,Email'],
            'senha'       => ['required', 'min:6', 'max:255'],
            'ativo'       => ['boolean'],
        ];
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;

        $this->nome        = $usuario->Nome;
        $this->usuarioNome = $usuario->Usuario;
        $this->email       = $usuario->Email;
        $this->senha       = $usuario->Senha;
        $this->ativo       = $usuario->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        Usuario::create([
            'Nome'    => $this->nome,
            'Usuario' => $this->usuarioNome,
            'Email'   => $this->email,
            'Senha'   => $this->senha,
            'Ativo'   => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->usuario->Nome    = $this->nome;
        $this->usuario->Usuario = $this->usuarioNome;
        $this->usuario->Email   = $this->email;
        $this->usuario->Senha   = $this->senha;
        $this->usuario->Ativo   = $this->ativo;

        $this->usuario->update();
    }
}
