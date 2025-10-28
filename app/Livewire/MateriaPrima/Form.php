<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?MateriaPrima $materiaPrima = null;

    public string $codigoAlternativo = '';

    public string $nome = '';

    public string $unidade = '';

    public float $valor = 0.00;

    public float $custoMedio = 0.00;

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'codigoAlternativo' => ['nullable', 'min:3', 'max:50'],
            'nome'              => ['required', 'min:3', 'max:255'],
            'unidade'           => ['required', 'in:L,KG'],
            'valor'             => ['nullable', 'min:0', 'numeric'],
            'custoMedio'        => ['nullable', 'min:0', 'numeric'],
            'ativo'             => ['boolean'],
        ];
    }

    public function setMateriaPrima(MateriaPrima $materiaPrima): void
    {
        $this->materiaPrima = $materiaPrima;

        $this->codigoAlternativo = $materiaPrima->CodigoAlternativo;
        $this->nome              = $materiaPrima->Nome;
        $this->unidade           = $materiaPrima->Unidade;
        $this->valor             = $materiaPrima->Valor;
        $this->custoMedio        = $materiaPrima->CustoMedio;
        $this->ativo             = $materiaPrima->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        MateriaPrima::create([
            'CodigoAlternativo' => $this->codigoAlternativo,
            'Nome'              => $this->nome,
            'Unidade'           => $this->unidade,
            'Valor'             => $this->valor,
            'CustoMedio'        => $this->custoMedio,
            'Ativo'             => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->materiaPrima->CodigoAlternativo = $this->codigoAlternativo;
        $this->materiaPrima->Nome              = $this->nome;
        $this->materiaPrima->Unidade           = $this->unidade;
        $this->materiaPrima->Valor             = $this->valor;
        $this->materiaPrima->CustoMedio        = $this->custoMedio;
        $this->materiaPrima->Ativo             = $this->ativo;

        $this->materiaPrima->update();
    }
}
