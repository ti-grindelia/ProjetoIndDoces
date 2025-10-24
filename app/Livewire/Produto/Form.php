<?php

namespace App\Livewire\Produto;

use App\Models\Produto;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Produto $produto = null;

    public string $codigoAlternativo = '';

    public string $descricao = '';

    public string $descritivo = '';

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'codigoAlternativo' => ['required', 'min:3', 'max:30'],
            'descricao'         => ['required', 'min:3', 'max:100'],
            'descritivo'        => ['required', 'min:3', 'max:255'],
            'ativo'             => ['boolean'],
        ];
    }

    public function setProduto(Produto $produto): void
    {
        $this->produto = $produto;

        $this->codigoAlternativo = $produto->CodigoAlternativo;
        $this->descricao         = $produto->Descricao;
        $this->descritivo        = $produto->Descritivo;
        $this->ativo             = $produto->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        Produto::create([
            'CodigoAlternativo' => $this->codigoAlternativo,
            'Descricao'         => $this->descricao,
            'Descritivo'        => $this->descritivo,
            'Ativo'             => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->produto->CodigoAlternativo = $this->codigoAlternativo;
        $this->produto->Descricao         = $this->descricao;
        $this->produto->Descritivo        = $this->descritivo;
        $this->produto->Ativo             = $this->ativo;

        $this->produto->update();
    }
}
