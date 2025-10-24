<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?MateriaPrima $materiaPrima = null;

    public string $nome = '';

    public string $descricao = '';

    public string $fornecedor = '';

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'nome'       => ['required', 'min:3', 'max:255'],
            'descricao'  => ['required'],
            'fornecedor' => ['required', 'min:3', 'max:255'],
            'ativo'      => ['boolean'],
        ];
    }

    public function setMateriaPrima(MateriaPrima $materiaPrima): void
    {
        $this->materiaPrima = $materiaPrima;

        $this->nome       = $materiaPrima->Nome;
        $this->descricao  = $materiaPrima->Descricao;
        $this->fornecedor = $materiaPrima->Fornecedor;
        $this->ativo      = $materiaPrima->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        MateriaPrima::create([
            'Nome'       => $this->nome,
            'Descricao'  => $this->descricao,
            'Fornecedor' => $this->fornecedor,
            'Ativo'      => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->materiaPrima->Nome       = $this->nome;
        $this->materiaPrima->Descricao  = $this->descricao;
        $this->materiaPrima->Fornecedor = $this->fornecedor;
        $this->materiaPrima->Ativo      = $this->ativo;

        $this->materiaPrima->update();
    }
}
