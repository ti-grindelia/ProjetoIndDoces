<?php

namespace App\Livewire\Pedido;

use App\Models\Pedido;
use App\Services\PedidoProcessamentoService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class Criar extends Component
{
    use WithFileUploads;

    use Toast;

    public Form $form;

    public int $proximoPedidoID;

    public string $dataHoraAtual;

    public $arquivo = null;

    public array $produtosProcessados = [];

    public array $produtosIndustriaDoces = [];

    public array $produtosIndustriaSalgados = [];

    public array $materiasTotais = [];

    public float $custoDoces = 0;

    public float $custoSalgados = 0;

    public function render(): View
    {
        return view('livewire.pedido.criar');
    }

    public function mount(): void
    {
        $ultimoID = Pedido::query()->max('PedidoID');
        $this->proximoPedidoID = ($ultimoID ?? 0) + 1;

        Carbon::setLocale('pt_BR');
        $this->dataHoraAtual = Carbon::now()->translatedFormat('d \d\e F \d\e Y, H:i');
    }

    public function processar(): void
    {
        $this->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $service = new PedidoProcessamentoService();
        $resultado = $service->processar($this->arquivo);

        $this->produtosProcessados = $resultado['produtosProcessados'];
        $this->produtosIndustriaDoces = $resultado['produtosIndustriaDoces'];
        $this->produtosIndustriaSalgados = $resultado['produtosIndustriaSalgados'];
        $this->materiasTotais = $resultado['materiasTotais'];
    }

    /**
     * @throws Throwable
     */
    public function concluirPedido(): void
    {
        $this->form->produtosIndustriaDoces     = $this->produtosIndustriaDoces;
        $this->form->produtosIndustriaSalgados  = $this->produtosIndustriaSalgados;

        $this->form->concluir();

        session()->flash('success');

        $this->success(
            title: 'Sucesso',
            description: 'Pedidos criados com sucesso',
            timeout: 5000,
        );

        $this->reset();
    }
}
