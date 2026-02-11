<?php

namespace App\Livewire\Produto;

use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class AtualizarValorIndustrializacao extends Component
{
    use Toast;

    public bool $modal = false;

    public float $porcentagemIndustrializacao = 0;

    public ?float $novaPorcentagemIndustrializacao = null;

    public function render(): View
    {
        return view('livewire.produto.atualizar-valor-industrializacao');
    }

    #[On('produto::atualizarIndustrializacao')]
    public function abrir(): void
    {
        $this->modal = true;
    }

    public function mount(): void
    {
        $produto = Produto::first();

        $custoMateriaPrima = $produto->CustoMateriaPrima;
        $custoIndustrializacao = $produto->CustoIndustrializacao;

        $this->porcentagemIndustrializacao = round($custoIndustrializacao / $custoMateriaPrima * 100);
    }

    public function salvar(): void
    {
        $this->validate([
            'novaPorcentagemIndustrializacao' => 'required|numeric|between:0,100'
        ]);

        $porcentagem = $this->novaPorcentagemIndustrializacao / 100;

        Produto::where('Ativo', true)
            ->update([
                'CustoIndustrializacao' => DB::raw("ROUND(CustoMateriaPrima * {$porcentagem}, 2)"),
                'CustoTotal' => DB::raw("ROUND(CustoMateriaPrima + (CustoMateriaPrima * {$porcentagem}), 2)")
            ]);

        $this->success(
                title: 'Sucesso',
                description: 'Valores atualizados com sucesso',
                timeout: 5000,
            );

        $this->modal = false;
        $this->reset('novaPorcentagemIndustrializacao');
    }
}
