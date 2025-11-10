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

    public string $categoria = '';

    public float $preco = 0.00;

    public float $custoMedio = 0.00;

    public float $pesoUnidade = 0.00;

    public ?int $empresa = null;

    public bool $fracionado = false;

    public bool $ativo = true;

    public function rules(): array
    {
        return [
            'codigoAlternativo' => ['nullable', 'min:3', 'max:30'],
            'descricao'         => ['required', 'min:3', 'max:100'],
            'descritivo'        => ['required', 'min:3', 'max:255'],
            'categoria'         => ['required', 'min:3', 'max:255'],
            'preco'             => ['required', 'min:0', 'numeric'],
            'custoMedio'        => ['nullable', 'min:0', 'numeric'],
            'pesoUnidade'       => ['nullable', 'min:0', 'numeric'],
            'empresa'           => ['nullable', 'integer', 'exists:Empresas,EmpresaID'],
            'fracionado'        => ['boolean'],
            'ativo'             => ['boolean'],
        ];
    }

    public function setProduto(Produto $produto): void
    {
        $this->produto = $produto;

        $this->codigoAlternativo = $produto->CodigoAlternativo;
        $this->descricao         = $produto->Descricao;
        $this->descritivo        = $produto->Descritivo;
        $this->categoria         = $produto->Categoria;
        $this->preco             = $produto->Preco;
        $this->custoMedio        = $produto->CustoMedio ?? 0.00;
        $this->pesoUnidade       = $produto->PesoUnidade ?? 0.00;
        $this->empresa           = $produto->EmpresaID ?? 0;
        $this->fracionado        = $produto->Fracionado;
        $this->ativo             = $produto->Ativo;
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->produto->EmpresaID = $this->empresa;
        $this->produto->CustoMedio = $this->custoMedio;
        $this->produto->pesoUnidade = $this->pesoUnidade;

        $this->produto->update();
    }
}
