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

        <x-button @click="$dispatch('produto::atualizarIndustrializacao')" label="Atualizar valor industrialização" icon="o-arrow-path" class="btn-warning text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_CodigoAlternativo', $headers)
        <x-tabela.th :$headers nome="codigoAlternativo"/>
        @endscope

        @scope('header_Descricao', $headers)
        <x-tabela.th :$headers nome="descricao"/>
        @endscope

        @scope('header_Categoria', $headers)
        <x-tabela.th :$headers nome="categoria"/>
        @endscope

        @scope('header_Preco', $headers)
        <x-tabela.th :$headers nome="preco"/>
        @endscope

        @scope('header_CustoMedio', $headers)
        <x-tabela.th :$headers nome="custoMedio"/>
        @endscope

        @scope('actions', $produto)
        <div class="flex items-center space-x-2">
            <div class="tooltip tooltip-left" data-tip="Editar Produto">
                <x-button
                    id="editar-btn-{{ $produto->ProdutoID }}"
                    wire:key="editar-btn-{{ $produto->ProdutoID }}"
                    icon="o-pencil"
                    @click="$dispatch('produto::atualizar', { id: {{ $produto->ProdutoID }} })"
                    spinner class="btn-sm btn-primary btn-soft"
                />
            </div>

            <div class="tooltip tooltip-left" data-tip="Matérias-primas">
                <x-button
                    id="materia-btn-{{ $produto->ProdutoID }}"
                    wire:key="materia-btn-{{ $produto->ProdutoID }}"
                    icon="o-eye-dropper"
                    @click="$dispatch('produto::relacionar-materia', { id: {{ $produto->ProdutoID }} })"
                    spinner class="btn-sm btn-secondary btn-soft"
                />
            </div>

{{--            @unless($produto->Ativo == false)--}}
{{--                <x-button--}}
{{--                    id="arquivar-btn-{{ $produto->ProdutoID }}"--}}
{{--                    wire:key="arquivar-btn-{{ $produto->ProdutoID }}"--}}
{{--                    icon="o-trash"--}}
{{--                    @click="$dispatch('produto::arquivar', { id: {{ $produto->ProdutoID }} })"--}}
{{--                    spinner class="btn-sm btn-error"--}}
{{--                />--}}
{{--            @else--}}
{{--                <x-button--}}
{{--                    id="restaurar-btn-{{ $produto->ProdutoID }}"--}}
{{--                    wire:key="restaurar-btn-{{ $produto->ProdutoID }}"--}}
{{--                    icon="o-arrow-uturn-left"--}}
{{--                    @click="$dispatch('produto::restaurar', { id: {{ $produto->ProdutoID }} })"--}}
{{--                    spinner class="btn-sm btn-warning"--}}
{{--                />--}}
{{--            @endunless--}}
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:produto.atualizar/>
    <livewire:produto.relacionar-materia-prima/>
    <livewire:produto.atualizar-valor-industrializacao/>
{{--    <livewire:produto.arquivar/>--}}
{{--    <livewire:produto.restaurar/>--}}
</div>
