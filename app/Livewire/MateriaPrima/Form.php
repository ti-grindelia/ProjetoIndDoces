<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?MateriaPrima $materiaPrima = null;

    public string $codigoAlternativo = '';

    public string $descricao = '';

    public string $unidade = '';

    public float $precoCompra = 0.00;

    public bool $permiteComposicao = false;

    public float $rendimento = 0.00;

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'codigoAlternativo' => ['nullable', 'min:3', 'max:50'],
            'descricao'         => ['required', 'min:3', 'max:255'],
            'unidade'           => ['required', 'in:L,KG'],
            'precoCompra'       => ['nullable', 'min:0', 'numeric'],
            'permiteComposicao' => ['boolean'],
            'rendimento'        => ['nullable', 'min:0', 'numeric'],
            'ativo'             => ['boolean'],
        ];
    }

    public function setMateriaPrima(MateriaPrima $materiaPrima): void
    {
        $this->materiaPrima = $materiaPrima;

        $this->codigoAlternativo = $materiaPrima->CodigoAlternativo;
        $this->descricao         = $materiaPrima->Descricao;
        $this->unidade           = $materiaPrima->Unidade;
        $this->precoCompra       = $materiaPrima->PrecoCompra;
        $this->permiteComposicao = $materiaPrima->PermiteComposicao;
        $this->rendimento        = $materiaPrima->Rendimento ?? 0.00;
        $this->ativo             = $materiaPrima->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        MateriaPrima::create([
            'CodigoAlternativo' => $this->codigoAlternativo,
            'Descricao'         => $this->descricao,
            'Unidade'           => $this->unidade,
            'PrecoCompra'       => $this->precoCompra,
            'PermiteComposicao' => $this->permiteComposicao,
            'Rendimento'        => $this->rendimento,
            'Ativo'             => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->materiaPrima->CodigoAlternativo = $this->codigoAlternativo;
        $this->materiaPrima->Descricao         = $this->descricao;
        $this->materiaPrima->Unidade           = $this->unidade;
        $this->materiaPrima->PrecoCompra       = $this->precoCompra;
        $this->materiaPrima->PermiteComposicao = $this->permiteComposicao;
        $this->materiaPrima->Rendimento        = $this->rendimento;
        $this->materiaPrima->Ativo             = $this->ativo;

        $this->materiaPrima->update();
    }
}
