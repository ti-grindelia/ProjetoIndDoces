<div>
    <x-header title="Matéria-Prima" separator/>

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

        <x-button @click="$dispatch('materia-prima::criar')" label="Nova matéria-prima" icon="o-plus" class="bg-blue-600 text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_MateriaPrimaID', $headers)
        <x-tabela.th :$headers nome="materiaPrimaID"/>
        @endscope

        @scope('header_Nome', $headers)
        <x-tabela.th :$headers nome="nome"/>
        @endscope

        @scope('header_Email', $headers)
        <x-tabela.th :$headers nome="email"/>
        @endscope

        @scope('actions', $materiaPrima)
        <div class="flex items-center space-x-2">
            <x-button
                id="editar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                wire:key="editar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                icon="o-pencil"
                @click="$dispatch('materia-prima::editar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                spinner class="btn-sm btn-primary"
            />

            @unless($materiaPrima->Ativo == false)
                <x-button
                    id="arquivar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    wire:key="arquivar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    icon="o-trash"
                    @click="$dispatch('materia-prima::arquivar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                    spinner class="btn-sm btn-error"
                />
            @else
                <x-button
                    id="restaurar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    wire:key="restaurar-btn-{{ $materiaPrima->MateriaPrimaID }}"
                    icon="o-arrow-uturn-left"
                    @click="$dispatch('materia-prima::restaurar', { id: {{ $materiaPrima->MateriaPrimaID }} })"
                    spinner class="btn-sm btn-warning"
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:materia-prima.criar/>
</div>
