<div>
    <x-header title="Produtos" separator/>

    <div class="flex justify-between mb-4 items-end">
        <div class="w-full flex space-x-4 items-end">
            <div class="w-1/3">
                <x-input
                    icon="o-magnifying-glass"
                    wire:model.live="pesquisa"
                    placeholder="Pesquise por nome e código"
                />
            </div>
            <x-select
                wire:model.live="porPagina"
                :options="[['id'=>5,'name'=>'5'],['id'=>15,'name'=>'15'],['id'=>25,'name'=>'25'],['id'=>50,'name'=>'50']]"
                placeholder="Registros por página"
            />
            <x-checkbox
                label="Mostrar produtos inativos"
                wire:model.live="pesquisaInativos"
                class="checkbox-info"
                right tight
            />
        </div>

        <x-button @click="$dispatch('produto::criar')" label="Novo produto" icon="o-plus" class="bg-blue-600 text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_ProdutoID', $headers)
        <x-tabela.th :$headers nome="produtoID"/>
        @endscope

        @scope('header_CodigoAlternativo', $headers)
        <x-tabela.th :$headers nome="codigoAlternativo"/>
        @endscope

        @scope('header_Descricao', $headers)
        <x-tabela.th :$headers nome="descricao"/>
        @endscope

        @scope('header_Descritivo', $headers)
        <x-tabela.th :$headers nome="descritivo"/>
        @endscope

        @scope('actions', $produto)
        <div class="flex items-center space-x-2">
            <x-button
                id="editar-btn-{{ $produto->ProdutoID }}"
                wire:key="editar-btn-{{ $produto->ProdutoID }}"
                icon="o-pencil"
                @click="$dispatch('produto::atualizar', { id: {{ $produto->ProdutoID }} })"
                spinner class="btn-sm btn-primary"
            />

            @unless($produto->Ativo == false)
                <x-button
                    id="arquivar-btn-{{ $produto->ProdutoID }}"
                    wire:key="arquivar-btn-{{ $produto->ProdutoID }}"
                    icon="o-trash"
                    @click="$dispatch('produto::arquivar', { id: {{ $produto->ProdutoID }} })"
                    spinner class="btn-sm btn-error"
                />
            @else
                <x-button
                    id="restaurar-btn-{{ $produto->ProdutoID }}"
                    wire:key="restaurar-btn-{{ $produto->ProdutoID }}"
                    icon="o-arrow-uturn-left"
                    @click="$dispatch('produto::restaurar', { id: {{ $produto->ProdutoID }} })"
                    spinner class="btn-sm btn-warning"
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:produto.criar/>
    <livewire:produto.atualizar/>
    <livewire:produto.arquivar/>
    <livewire:produto.restaurar/>
</div>
