<?php

namespace App\Livewire\Produto;

use App\Models\Produto;
use App\Services\ProdutoMateriaPrimaService;
use Illuminate\Contracts\View\View;
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

    private ProdutoMateriaPrimaService $service;

    public array $cabecalho = [
        ['key' => 'CodigoAlternativo', 'label' => '#'],
        ['key' => 'Descricao', 'label' => 'Descrição'],
        ['key' => 'Unidade', 'label' => 'Unidade'],
        ['key' => 'Quantidade', 'label' => 'Quantidade'],
        ['key' => 'CustoUnitario', 'label' => 'Custo unit.'],
        ['key' => 'Custo', 'label' => 'Custo'],
    ];

    public array $expanded = [];

    public function boot(ProdutoMateriaPrimaService $service): void
    {
        $this->service = $service;
    }

    public function render(): View
    {
        return view('livewire.produto.relacionar-materia-prima');
    }

    public function mount(): void
    {
        $this->materiasParaPesquisar = $this->service->pesquisarMaterias();
    }

    #[On('produto::relacionar-materia')]
    public function carregar(int $id): void
    {
        $this->produto = Produto::query()->where('Ativo', true)->findOrFail($id);

        $this->materiasSelecionadas = $this->service->carregarMaterias($this->produto);

        $this->atualizarTotais();
        $this->modal = true;
    }

    public function pesquisarMateria(?string $valor = null): void
    {
        $this->materiasParaPesquisar = $this->service->pesquisarMaterias($valor);
    }

    public function adicionarMateria(): void
    {
        $this->materiasSelecionadas = $this->service->adicionarMateria(
            $this->materiaPesquisada,
            $this->quantidadeMateria,
            $this->materiasSelecionadas
        );

        $this->atualizarTotais();
        $this->reset('materiaPesquisada', 'quantidadeMateria');
    }

    #[On('materia::excluir')]
    public function removerMateria(int $id): void
    {
        $this->materiasSelecionadas = $this->service->removerMateria($id, $this->materiasSelecionadas);
        $this->atualizarTotais();
    }

    public function salvar(): void
    {
        $this->service->salvar($this->produto, $this->materiasSelecionadas, $this->custoMaterialDireto);

        $this->modal = false;
        $this->reset('materiasSelecionadas');

        $this->dispatch('produto::recarregar')->to('produto.index');
    }

    private function atualizarTotais(): void
    {
        $t = $this->service->calcularTotais(
            $this->materiasSelecionadas,
            $this->produto->RendimentoProducao ?? 0
        );

        $this->custoMaterialDireto = $t['custoMaterialDireto'];
        $this->pesoTotal = $t['pesoTotal'];
        $this->custoPorUnidade = $t['custoPorUnidade'];
        $this->rendimento = $t['rendimento'];
    }
}
