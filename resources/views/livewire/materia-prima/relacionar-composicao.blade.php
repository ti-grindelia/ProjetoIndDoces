<x-modal wire:model="modal" title="Composição da matéria-prima" separator class="backdrop-blur" box-class="min-w-3xl"
    subtitle="Você está relacionando os ingredientes da matéria-prima {{ $materiaBase?->Descricao }}">

    <x-form wire:submit="adicionarMateria" id="adicionar-materia-form">
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-2/3">
                <x-choices
                    :options="$materiasParaPesquisar"
                    wire:model="materiaFilhaPesquisada"
                    label="Ingrediente"
                    search-function="pesquisarMateria"
                    searchable
                    no-result-text="Nenhum resultado encontrado"
                    single
                    required
                    clearable
                />
            </div>

            <div class="flex-1">
                <x-input
                    label="Quantidade"
                    placeholder="Digite a quantidade"
                    wire:model="quantidadeMateria"
                    required
                />
            </div>
        </div>

        <x-button type="submit" class="w-full mb-4" icon="o-plus" label="Adicionar" form="adicionar-materia-form"/>
    </x-form>

    @if ($materiasSelecionadas)
        <x-form id="salvar-materias-form">
            <x-table :headers="$cabecalho" :rows="$materiasSelecionadas">
                @scope('actions', $materia)
                <div class="flex items-center space-x-2">
                    <x-button
                        id="excluir-btn-{{ $materia['MateriaPrimaID'] }}"
                        wire:key="excluir-btn-{{ $materia['MateriaPrimaID'] }}"
                        icon="o-trash"
                        @click="$dispatch('materia::excluir', { id: {{ $materia['MateriaPrimaID'] }} })"
                        spinner class="btn-sm btn-error"
                    />
                </div>
                @endscope
            </x-table>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.modal = false"/>
                <x-button wire:click="salvar" label="Salvar" form="salvar-materias-form" class="bg-blue-600 text-white"/>
            </x-slot:actions>
        </x-form>
    @endif

</x-modal>
