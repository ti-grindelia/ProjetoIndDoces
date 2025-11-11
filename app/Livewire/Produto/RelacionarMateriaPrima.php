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

    public float $custoMaterialDireto = 0;

    public float $pesoTotal = 0;

    public float $rendimento = 0;

    public float $custoPorUnidade = 0;

    public array $cabecalho = [
        ['key' => 'CodigoAlternativo', 'label' => '#'],
        ['key' => 'Descricao', 'label' => 'Descrição'],
        ['key' => 'Unidade', 'label' => 'Unidade'],
        ['key' => 'Quantidade', 'label' => 'Quantidade'],
        ['key' => 'CustoUnitario', 'label' => 'Custo unit.'],
        ['key' => 'Custo', 'label' => 'Custo'],
    ];

    public array $expanded = [];

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
                'Descricao' => $relacao->materiaPrima->Descricao ?? '',
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

    public function updatedMateriaPesquisada($id): void
    {
        $materia = MateriaPrima::find($id);

        if ($materia && $materia->PermiteComposicao && $materia->Rendimento) {
            $this->quantidadeMateria = $materia->Rendimento;
        } else {
            $this->quantidadeMateria = null;
        }
    }

    public function adicionarMateria(): void
    {
        if (!$this->materiaPesquisada || !$this->quantidadeMateria) {
            return;
        }

        $materia = MateriaPrima::with('componentes')->find($this->materiaPesquisada);

        if (!$materia) return;

        if (collect($this->materiasSelecionadas)->contains('MateriaPrimaID', $materia->MateriaPrimaID)) {
            return;
        }

        $quantidade = $materia->PermiteComposicao && $materia->Rendimento
            ? $materia->Rendimento
            : $this->quantidadeMateria;

        $custoTotal = $materia->PrecoCompra * $quantidade;

        $composicoes = [];
        if ($materia->PermiteComposicao && $materia->componentes->isNotEmpty()) {
            $composicoes = $materia->componentes->map(function ($comp) {
                $quant = $comp->pivot->Quantidade ?? 0;
                $custoUnit = $comp->PrecoCompra ?? 0;
                $custoTot = $custoUnit * $quant;

                return [
                    'CodigoAlternativo' => $comp->CodigoAlternativo,
                    'Descricao' => $comp->Descricao,
                    'Unidade' => $comp->Unidade,
                    'Quantidade' => $quant,
                    'CustoUnitario' => $custoUnit,
                    'CustoTotal' => $custoTot,
                ];
            })->toArray();
        }

        $this->materiasSelecionadas[] = [
            'MateriaPrimaID' => $materia->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo,
            'Descricao' => $materia->Descricao,
            'Unidade' => $materia->Unidade,
            'Quantidade' => $this->quantidadeMateria,
            'CustoUnitario' => $materia->PrecoCompra,
            'Custo' => number_format($custoTotal, 2),
            'PermiteComposicao' => $materia->PermiteComposicao,
            'Composicoes' => $composicoes,
        ];

        $this->recalcularTotais();
        $this->reset('materiaPesquisada', 'quantidadeMateria');;
    }

    #[On('materia::excluir')]
    public function removerMateria($id): void
    {
        $this->materiasSelecionadas = array_filter(
            $this->materiasSelecionadas,
            fn($materia) => $materia['MateriaPrimaID'] !== $id
        );

        $this->recalcularTotais();
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

        Produto::find($this->produto->ProdutoID)
            ->update(['CustoMedio' => $this->custoMaterialDireto]);

        $this->modal = false;
        $this->reset('materiasSelecionadas');

        $this->dispatch('produto::recarregar')->to('produto.index');
    }

    private function recalcularTotais(): void
    {
        $this->custoMaterialDireto = 0;
        $this->pesoTotal = 0;
        $this->custoPorUnidade = 0;

        $pesoPorUnidade = (float) ($this->produto->PesoUnidade ?? 0);

        foreach ($this->materiasSelecionadas as $materia) {
            $quant = (float) ($materia['Quantidade'] ?? 0);
            $custoUnit = (float) ($materia['CustoUnitario'] ?? 0);
            $custo = $quant * $custoUnit;

            $this->custoMaterialDireto += $custo;
            $this->pesoTotal += $quant;
        }

        if ($pesoPorUnidade > 0) {
            $this->rendimento = round($this->pesoTotal / $pesoPorUnidade, 2);
        } else {
            $this->rendimento = 0;
        }

        if ($this->rendimento > 0) {
            $this->custoPorUnidade = $this->custoMaterialDireto / $this->rendimento;
        }

        $this->custoMaterialDireto = number_format($this->custoMaterialDireto, 2);
        $this->pesoTotal = number_format($this->pesoTotal, 4);
        $this->rendimento = number_format($this->rendimento, 2);
        $this->custoPorUnidade = number_format($this->custoPorUnidade, 2);
    }
}
