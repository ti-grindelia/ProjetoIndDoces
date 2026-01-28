<x-card class="py-4 mt-10">
    <div class="flex flex-row justify-start items-center gap-16">
        <label class="block text-lg font-semibold">
            Pedido #{{ $proximoPedidoID }}
        </label>
        <div class="flex flex-row gap-2 text-md font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/>
            </svg>
            {{ $dataHoraAtual }}
        </div>
        <div class="w-1/8 text-md font-medium">
            <x-select
                    :options="[
                    ['id'=>'Aberto','name'=>'Aberto'],
                    ['id'=>'Producao','name'=>'Produção','disabled'=>true],
                    ['id'=>'Finalizado','name'=>'Finalizado','disabled'=>true]
                ]"
            />
        </div>
    </div>

    <x-card class="mt-4 py-4">
        <label class="block text-md font-semibold">
            Pedido Itens
        </label>

        <hr class="my-4">

        <x-form wire:submit="adicionarProduto" id="adicionar-produto-form">
            <div class="flex flex-row gap-4 items-end">
                <div class="w-1/3">
                    <x-choices
                            label="Produto"
                            wire:model="form.produtoPesquisado"
                            :options="$produtosParaPesquisar"
                            searchable
                            search-function="pesquisarProduto"
                            no-result-text="Nenhum resultado encontrado"
                            single
                            required
                            placeholder="Selecione um produto"
                            clearable
                    />
                </div>
                <div class="w-1/5">
                    <x-input
                            label="Quantidade"
                            type="number"
                            min="0"
                            step="0.1"
                            wire:model="form.quantidade"
                            placeholder="Informe a quantidade"
                            required
                    />
                </div>
                <div class="w-1/5">
                    <x-button label="Adicionar Produto" type="submit" class="bg-blue-600 text-white"
                              form="adicionar-produto-form"/>
                </div>
            </div>
        </x-form>

        @if($form->produtosSelecionados)
            <x-form id="confirmar-pedido-form" class="mt-4">
                <x-table :headers="$form->cabecalho" :rows="$form->produtosSelecionados"
                         wire:model="form.expanded"
                         expandable
                         expandable-key="ProdutoID"
                         expandable-condition="PodeExpandir"
                >

                    @scope('expansion', $produto)
                    @if(!empty($produto['MateriasPrimas']))
                        @include('components.pedidos.materias-tabela', ['materias' => $produto['MateriasPrimas']])
                    @endif
                    @endscope

                    @scope('actions', $produto)
                    <div class="flex items-center space-x-2">
                        <x-button
                                id="atualizar-btn-{{ $produto['ProdutoID'] }}"
                                wire:key="atualizar-btn-{{ $produto['ProdutoID'] }}"
                                icon="o-pencil"
                                @click="$dispatch('produto-pedido::atualizar', { id: {{ $produto['ProdutoID'] }} })"
                                spinner class="btn-sm btn-primary"
                        />

                        <x-button
                                id="excluir-btn-{{ $produto['ProdutoID'] }}"
                                wire:key="excluir-btn-{{ $produto['ProdutoID'] }}"
                                icon="o-trash"
                                @click="$dispatch('produto-pedido::excluir', { id: {{ $produto['ProdutoID'] }} })"
                                spinner class="btn-sm btn-error"
                        />
                    </div>
                    @endscope
                </x-table>
            </x-form>
        @endif
    </x-card>
</x-card>
