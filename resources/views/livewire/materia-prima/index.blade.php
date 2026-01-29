<div>
    <x-header title="Matérias-Primas" separator/>

    <div class="flex justify-between mb-4 items-end">
        <div class="w-full flex space-x-4 items-end">
            <div class="w-1/3">
                <x-input
                    icon="o-magnifying-glass"
                    wire:model.live="pesquisa"
                    placeholder="Pesquise por nome"
                />
            </div>
            <x-select
                wire:model.live="porPagina"
                :options="[['id'=>5,'name'=>'5'],['id'=>15,'name'=>'15'],['id'=>25,'name'=>'25'],['id'=>50,'name'=>'50']]"
                placeholder="Registros por página"
            />
            <x-checkbox
                label="Mostrar matérias inativas"
                wire:model.live="pesquisaInativos"
                class="checkbox-info"
                right tight
            />
        </div>

        <x-button @click="$dispatch('materia-prima::criar')" label="Nova matéria-prima" icon="o-plus" class="bg-blue-600 text-white mr-4"/>

        <x-button @click="$dispatch('materia-prima::atualizarValores')" label="Atualizar valores" icon="o-arrow-path" class="btn-warning text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">

        @scope('header_CodAlternativo', $headers)
        <x-tabela.th :$headers nome="codAlternativo"/>
        @endscope

        @scope('header_Descricao', $headers)
        <x-tabela.th :$headers nome="descricao"/>
        @endscope

        @scope('header_Unidade', $headers)
        <x-tabela.th :$headers nome="unidade"/>
        @endscope

        @scope('header_PrecoCompra', $headers)
        <x-tabela.th :$headers nome="precoCompra"/>
        @endscope


        @scope('actions', $materiaPrima)
        <div class="flex items-center space-x-2">
            <div class="tooltip tooltip-left" data-tip="Editar matéria-prima">
                <x-button
                    id="editar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    wire:key="editar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    icon="o-pencil"
                    @click="$dispatch('materia-prima::atualizar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                    spinner class="btn-sm btn-primary"
                />
            </div>

            @if($materiaPrima->PermiteComposicao)
                <div class="tooltip tooltip-left" data-tip="Montar composição">
                    <x-button
                        id="composicao-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        wire:key="composicao-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        icon="o-bars-3-center-left"
                        @click="$dispatch('materia::relacionar-composicao', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                        spinner class="btn-sm btn-secondary"
                    />
                </div>
            @endif

            @unless($materiaPrima->Ativo == false)
                <div class="tooltip tooltip-left" data-tip="Excluir matéria-prima">
                    <x-button
                        id="arquivar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        wire:key="arquivar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        icon="o-trash"
                        @click="$dispatch('materia-prima::arquivar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                        spinner class="btn-sm btn-error"
                    />
                </div>
            @else
                <div class="tooltip tooltip-left" data-tip="Restaurar matéria-prima">
                    <x-button
                        id="restaurar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        wire:key="restaurar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                        icon="o-arrow-uturn-left"
                        @click="$dispatch('materia-prima::restaurar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                        spinner class="btn-sm btn-warning"
                    />
                </div>
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:materia-prima.criar/>
    <livewire:materia-prima.atualizar/>
    <livewire:materia-prima.relacionar-composicao/>
    <livewire:materia-prima.arquivar/>
    <livewire:materia-prima.restaurar/>
    <livewire:materia-prima.atualizar-valores-excel/>
</div>
