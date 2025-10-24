<?php

namespace App\Livewire\Produto;

use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Atualizar extends Component
{
    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.produto.atualizar');
    }

    #[On('produto::atualizar')]
    public function carregar(int $id): void
    {
        $produto = Produto::find($id);

        $this->form->setProduto($produto);
        $this->form->resetErrorBag();
        $this->modal = true;
    }

    public function salvar(): void
    {
        $this->form->atualizar();

        $this->modal = false;
        $this->dispatch('produto::recarregar')->to('produto.index');
    }
}
