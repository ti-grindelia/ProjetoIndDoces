<?php

namespace App\Livewire\Pedido;

use App\Models\MateriaPrima;
use App\Models\Produto;
use App\Models\ProdutoMateriaPrima;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?int $produtoPesquisado = null;

    public ?float $quantidade = null;

    public array $produtosSelecionados = [];

    public array $cabecalho = [
        ['key' => 'CodigoAlternativo', 'label' => '#'],
        ['key' => 'Descricao', 'label' => 'Descrição'],
        ['key' => 'Categoria', 'label' => 'Categoria'],
        ['key' => 'Custo', 'label' => 'Custo'],
        ['key' => 'Quantidade', 'label' => 'Quantidade']
    ];

    public array $expanded = [];

    protected $rules = [
        'quantidade'        => ['required', 'numeric', 'min:0.1'],
        'produtoPesquisado' => ['required', 'integer', 'exists:Produto,ProdutoID'],
    ];

    public function adicionarProduto(): void
    {
        if (!$this->produtoPesquisado || !$this->quantidade) {
            return;
        }

        $produto = Produto::query()
            ->where('Ativo', true)
            ->find($this->produtoPesquisado);

        if (!$produto) return;

        $materias = ProdutoMateriaPrima::query()
            ->where('ProdutoID', $this->produtoPesquisado)
            ->with(['materiaPrima', 'materiaPrima.componentes'])
            ->get();

        if ($produto->Fracionado == 0 && floor($this->quantidade) !== $this->quantidade) {
            $this->addError('quantidade', 'Este produto não pode ser fracionado');
            return;
        }

        $this->produtosSelecionados[] = [
            'ProdutoID' => $produto->ProdutoID,
            'CodigoAlternativo' => $produto->CodigoAlternativo,
            'Descricao' => $produto->Descricao,
            'Categoria' => $produto->Categoria,
            'Custo' => $produto->CustoMedio,
            'Quantidade' => $this->quantidade,
            'MateriasPrimas' => $materias->toArray(),
            'PodeExpandir' => $materias->isNotEmpty(),
        ];

        $this->reset('produtoPesquisado', 'quantidade');
        $this->resetErrorBag();
    }
}
