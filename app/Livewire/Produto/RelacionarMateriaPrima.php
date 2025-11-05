<?php

namespace App\Livewire\Produto;

use App\Models\MateriaPrima;
use App\Models\Produto;
use App\Models\ProdutoMateriaPrima;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class RelacionarMateriaPrima extends Component
{
    public Produto $produto;

    public bool $modal = false;

    public ?int $materiaPesquisada = null;

    public ?float $quantidadeMateria = null;

    public Collection $materiasParaPesquisar;

    public array $materiasSelecionadas = [];

    public array $cabecalho = [
        ['key' => 'CodigoAlternativo', 'label' => '#'],
        ['key' => 'Nome', 'label' => 'Nome'],
        ['key' => 'Unidade', 'label' => 'Unidade'],
        ['key' => 'Quantidade', 'label' => 'Quantidade'],
        ['key' => 'CustoUnitario', 'label' => 'Custo unit.'],
        ['key' => 'Custo', 'label' => 'Custo'],
    ];

    public function render(): View
    {
        return view('livewire.produto.relacionar-materia-prima');
    }

    public function mount(): void
    {
        $this->pesquisarMateria();
    }

    #[On('produto::relacionar-materia')]
    public function carregar(int $id): void
    {
        $this->produto = Produto::query()->where('Ativo', true)->findOrFail($id);

        $this->materiasSelecionadas = ProdutoMateriaPrima::query()
            ->where('ProdutoID', $this->produto->ProdutoID)
            ->with('materiaPrima')
            ->get()
            ->map(fn($relacao) => [
                'MateriaPrimaID' => $relacao->MateriaPrimaID,
                'CodigoAlternativo' => $relacao->materiaPrima->CodigoAlternativo ?? '',
                'Nome' => $relacao->materiaPrima->Descricao ?? '',
                'Unidade' => $relacao->Unidade,
                'Quantidade' => $relacao->Quantidade,
                'CustoUnitario' => $relacao->CustoUnitario,
                'Custo' => number_format($relacao->Custo, 2),
            ])
            ->toArray();

        $this->modal = true;
    }

    public function pesquisarMateria(?string $valor = null): void
    {
        $this->materiasParaPesquisar = MateriaPrima::query()
            ->when($valor, fn(Builder $q) => $q->where('Descricao', 'like', "%{$valor}%"))
            ->where('Ativo', true)
            ->orderBy('Descricao')
            ->get()
            ->map(fn($materia) => [
                'id' => $materia->MateriaPrimaID,
                'name' => $materia->Descricao . ' - ' . $materia->Unidade,
            ]);
    }

    public function adicionarMateria(): void
    {
        if (!$this->materiaPesquisada || !$this->quantidadeMateria) {
            return;
        }

        $materia = MateriaPrima::find($this->materiaPesquisada);

        if (!$materia) return;

        if (collect($this->materiasSelecionadas)->contains('MateriaPrimaID', $materia->MateriaPrimaID)) {
            return;
        }

        $this->materiasSelecionadas[] = [
            'MateriaPrimaID' => $materia->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo,
            'Descricao' => $materia->Nome,
            'Unidade' => $materia->Unidade,
            'Quantidade' => $this->quantidadeMateria,
            'CustoUnitario' => $materia->PrecoCompra,
            'Custo' => number_format($materia->PrecoCompra * $this->quantidadeMateria, 2, '.', ','),
        ];

        $this->materiaPesquisada = null;
        $this->quantidadeMateria = null;
    }

    #[On('materia::excluir')]
    public function removerMateria($id): void
    {
        $this->materiasSelecionadas = array_filter(
            $this->materiasSelecionadas,
            fn($materia) => $materia['MateriaPrimaID'] !== $id
        );
    }

    public function salvar(): void
    {
        if (empty($this->materiasSelecionadas)) {
            return;
        }

        foreach ($this->materiasSelecionadas as $materia) {
            ProdutoMateriaPrima::updateOrCreate(
                [
                    'ProdutoID' => $this->produto->ProdutoID,
                    'MateriaPrimaID' => $materia['MateriaPrimaID'],
                ],
                [
                    'Unidade' => $materia['Unidade'],
                    'Quantidade' => $materia['Quantidade'],
                    'CustoUnitario' => $materia['CustoUnitario'],
                    'Custo' => $materia['Custo'],
                ]
            );
        }

        $this->modal = false;
        $this->reset('materiasSelecionadas');
    }
}
