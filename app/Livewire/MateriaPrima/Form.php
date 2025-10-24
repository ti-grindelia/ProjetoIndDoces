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

    public function rules(): array
    {
        return [
            'nome'       => ['required', 'min:3', 'max:255'],
            'descricao'  => ['required'],
            'fornecedor' => ['required', 'min:3', 'max:255'],
        ];
    }

    public function setMateriaPrima(MateriaPrima $materiaPrima): void
    {
        $this->materiaPrima = $materiaPrima;

        $this->nome = $materiaPrima->Nome;
        $this->descricao = $materiaPrima->Descricao;
        $this->fornecedor = $materiaPrima->Fornenecedor;
    }

    public function criar(): void
    {
        $this->validate();

        MateriaPrima::create([
            'Nome'       => $this->nome,
            'Descricao'  => $this->descricao,
            'Fornecedor' => $this->fornecedor,
        ]);

        $this->reset();
    }
}
