<?php

namespace App\Livewire\MateriaPrima;

use App\Models\MateriaPrima;
use App\Services\MateriaPrimaComposicaoService;
use Illuminate\Contracts\View\View;
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

    private MateriaPrimaComposicaoService $service;

    public array $cabecalho = [
        ['key' => 'CodigoAlternativo', 'label' => '#'],
        ['key' => 'Descricao', 'label' => 'Descricao'],
        ['key' => 'Unidade', 'label' => 'Unidade'],
        ['key' => 'Quantidade', 'label' => 'Quantidade'],
        ['key' => 'CustoUnitario', 'label' => 'Custo unit.'],
        ['key' => 'Custo', 'label' => 'Custo'],
    ];

    public function boot(MateriaPrimaComposicaoService $service): void
    {
        $this->service = $service;
    }

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
        $this->materiasSelecionadas = $this->service->carregarComposicoes($this->materiaBase);
        $this->modal = true;
    }

    public function pesquisarMateria(?string $valor = null): void
    {
        $this->materiasParaPesquisar = $this->service->pesquisar($valor);
    }

    public function adicionarMateria(): void
    {
        if (!$this->materiaFilhaPesquisada || !$this->quantidadeMateria) return;

        $materia = MateriaPrima::query()->find($this->materiaFilhaPesquisada);
        if (!$materia) return;

        if (collect($this->materiasSelecionadas)->contains('MateriaPrimaID', $materia->MateriaPrimaID)) {
            return;
        }

        $this->materiasSelecionadas[] = $this->service->adicionar(
            $materia,
            $this->quantidadeMateria
        );

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

        $this->service->salvarComposicoes(
            $this->materiaBase,
            $this->materiasSelecionadas
        );

        $this->reset('materiasSelecionadas');
        $this->modal = false;
    }
}
