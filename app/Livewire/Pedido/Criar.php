<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class Criar extends Component
{
    public Form $form;

    public int $proximoPedidoID;

    public string $dataHoraAtual;

    public Collection $produtosParaPesquisar;

    public function render(): View
    {
        return view('livewire.pedido.criar');
    }

    public function mount(): void
    {
        $ultimoID = Pedido::max('PedidoID');
        $this->proximoPedidoID = ($ultimoID ?? 0) + 1;

        Carbon::setLocale('pt_BR');
        $this->dataHoraAtual = Carbon::now()->translatedFormat('d \d\e F \d\e Y, H:i');

        $this->pesquisarProduto();
    }

    public function pesquisarProduto(?string $valor = null): void
    {
        $this->produtosParaPesquisar = Produto::query()
            ->when($valor, fn(Builder $q) => $q->where('Descricao', 'like', "%{$valor}%"))
            ->where('Ativo', true)
            ->orderBy('Descricao')
            ->get()
            ->map(fn($produto) => [
                'id' => $produto->ProdutoID,
                'name' => $produto->Descricao,
            ]);
    }

    public function adicionarProduto(): void
    {
        $this->form->adicionarProduto();
    }
}
