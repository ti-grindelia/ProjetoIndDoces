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

    public float $rendimentoProducao = 0.00;

    public ?int $empresa = null;

    public bool $fracionado = false;

    public bool $ativo = true;

    public float $custoMateriaPrima = 0.00;

    public float $custoIndustrializacao = 0.00;

    public float $custoTotal = 0.00;

    public float $mvaPercentual = 0.00;

    public float $icmsPercentual = 0.00;

    public function rules(): array
    {
        return [
            'codigoAlternativo'  => ['nullable', 'min:3', 'max:30'],
            'descricao'          => ['required', 'min:3', 'max:100'],
            'descritivo'         => ['required', 'min:3', 'max:255'],
            'categoria'          => ['required', 'min:3', 'max:255'],
            'preco'              => ['required', 'min:0', 'numeric'],
            'custoMedio'         => ['nullable', 'min:0', 'numeric'],
            'pesoUnidade'        => ['nullable', 'min:0', 'numeric'],
            'rendimentoProducao' => ['nullable', 'min:0', 'numeric'],
            'empresa'            => ['nullable', 'integer', 'exists:Empresas,EmpresaID'],
            'fracionado'         => ['boolean'],
            'ativo'              => ['boolean'],
            'custoMateriaPrima'  => ['nullable', 'min:0', 'numeric'],
            'custoIndustrializacao' => ['nullable', 'min:0', 'numeric'],
            'custoTotal'         => ['nullable', 'min:0', 'numeric'],
            'mvaPercentual'      => ['nullable', 'min:0', 'numeric'],
            'icmsPercentual'     => ['nullable', 'min:0', 'numeric'],
        ];
    }

    public function setProduto(Produto $produto): void
    {
        $this->produto = $produto;

        $this->codigoAlternativo  = $produto->CodigoAlternativo;
        $this->descricao          = $produto->Descricao;
        $this->descritivo         = $produto->Descritivo;
        $this->categoria          = $produto->Categoria;
        $this->preco              = $produto->Preco;
        $this->custoMedio         = $produto->CustoMedio ?? 0.00;
        $this->pesoUnidade        = $produto->PesoUnidade ?? 0.00;
        $this->rendimentoProducao = $produto->RendimentoProducao ?? 0.00;
        $this->empresa            = $produto->EmpresaID ?? 0;
        $this->fracionado         = $produto->Fracionado;
        $this->ativo              = $produto->Ativo;
        $this->custoMateriaPrima  = $produto->CustoMateriaPrima ?? $produto->CustoMedio ?? 0.00;
        $this->custoIndustrializacao = $produto->CustoIndustrializacao ?? 0.00;
        $this->custoTotal         = $produto->CustoTotal ?? 0.00;
        $this->mvaPercentual      = $produto->MVAPercentual ?? 0.00;
        $this->icmsPercentual     = $produto->ICMSPercentual ?? 0.00;
    }

    public function updated($property): void
    {
        if (in_array($property, [
            'custoMateriaPrima',
            'custoIndustrializacao',
        ])) {
            $this->recalcularCustoTotal();
        }
    }

    private function recalcularCustoTotal(): void
    {
        $this->custoTotal = round(
            (float) $this->custoMateriaPrima + (float) $this->custoIndustrializacao,
            2
        );
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->produto->EmpresaID          = $this->empresa;
        $this->produto->CustoMedio         = $this->custoMedio;
        $this->produto->pesoUnidade        = $this->pesoUnidade;
        $this->produto->RendimentoProducao = $this->rendimentoProducao;
        $this->produto->CustoMateriaPrima  = $this->custoMateriaPrima;
        $this->produto->CustoIndustrializacao = $this->custoIndustrializacao;
        $this->produto->CustoTotal         = $this->custoTotal;
        $this->produto->MVAPercentual      = $this->mvaPercentual;
        $this->produto->ICMSPercentual     = $this->icmsPercentual;

        $this->produto->update();
    }
}
