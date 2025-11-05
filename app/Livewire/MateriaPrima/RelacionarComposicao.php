<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use App\Models\MateriaPrimaComposicao;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class RelacionarComposicao extends Component
{
    public MateriaPrima $materiaBase;

    public bool $modal = false;

    public ?int $materiaFilhaPesquisada = null;

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
        return view('livewire.materia-prima.relacionar-composicao');
    }

    public function mount(): void
    {
        $this->pesquisarMateria();
    }

    #[On('materia::relacionar-composicao')]
    public function carregar(int $id): void
    {
        $this->materiaBase = MateriaPrima::query()->findOrFail($id);

        $this->materiasSelecionadas = MateriaPrimaComposicao::query()
            ->where('MateriaPrimaBaseID', $this->materiaBase->MateriaPrimaID)
            ->with('materiaFilha')
            ->get()
            ->map(fn($relacao) => [
                'MateriaPrimaID' => $relacao->MateriaPrimaFilhaID,
                'CodigoAlternativo' => $relacao->materiaFilha->CodigoAlternativo ?? '',
                'Descricao' => $relacao->materiaFilha->Descricao ?? '',
                'Unidade' => $relacao->materiaFilha->Unidade ?? '',
                'Quantidade' => $relacao->Quantidade,
                'CustoUnitario' => $relacao->CustoUnitario,
                'Custo' => number_format($relacao->CustoTotal, 2, '.', ','),
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
        if (!$this->materiaFilhaPesquisada || !$this->quantidadeMateria) {
            return;
        }

        $materia = MateriaPrima::find($this->materiaFilhaPesquisada);

        if (!$materia) return;

        if (collect($this->materiasSelecionadas)->contains('MateriaPrimaID', $materia->MateriaPrimaID)) {
            return;
        }

        $custoTotal = $materia->PrecoCompra * $this->quantidadeMateria;

        $this->materiasSelecionadas[] = [
            'MateriaPrimaID' => $materia->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo,
            'Nome' => $materia->Descricao,
            'Unidade' => $materia->Unidade,
            'Quantidade' => $this->quantidadeMateria,
            'CustoUnitario' => $materia->PrecoCompra,
            'Custo' => number_format($custoTotal, 2, '.', ','),
        ];

        $this->materiaFilhaPesquisada = null;
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

        $custoTotalBase = 0;

        foreach ($this->materiasSelecionadas as $materia) {
            $custo = (float) str_replace(',', '.', $materia['Custo']);
            $custoTotalBase += $custo;

            MateriaPrimaComposicao::updateOrCreate(
                [
                    'MateriaPrimaBaseID' => $this->materiaBase->MateriaPrimaID,
                    'MateriaPrimaFilhaID' => $materia['MateriaPrimaID'],
                ],
                [
                    'Quantidade' => $materia['Quantidade'],
                    'CustoUnitario' => $materia['CustoUnitario'],
                    'CustoTotal' => $custo,
                ]
            );
        }

        $rendimento = $this->materiaBase->Rendimento ?: 1;

        $precoCompra = $rendimento > 0 ? $custoTotalBase / $rendimento : $custoTotalBase;

        $this->materiaBase->update([
            'PrecoCompra' => $precoCompra,
        ]);

        $this->reset('materiasSelecionadas');
        $this->modal = false;
    }
}
