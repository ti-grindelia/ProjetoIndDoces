<x-modal wire:model="modal" title="Relacionar matérias-primas" separator class="backdrop-blur" box-class="min-w-3xl"
    subtitle="Você está relacionando as matérias-primas referentes ao produto {{ $produto?->Descricao }}">

    <x-form wire:submit="adicionarMateria" id="adicionar-materia-form">
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-2/3">
                <x-choices
                    :options="$materiasParaPesquisar"
                    wire:model.live="materiaPesquisada"
                    label="Matéria-prima"
                    search-function="pesquisarMateria"
                    searchable
                    no-result-text="Nenhum resultado encontrado"
                    single
                    required
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
            <x-table :headers="$cabecalho" :rows="$materiasSelecionadas" wire:model="expanded" expandable expandable-key="MateriaPrimaID"
                expandable-condition="PermiteComposicao">

                @scope('expansion', $materia)
                    @if (!empty($materia['Composicoes']))
                        <div class="px-6">
                            <table class="w-full text-xs" style="padding-left: 1.5rem">
                                <colgroup>
                                    <col style="width: 10%">
                                    <col style="width: 30%">
                                    <col style="width: 10%">
                                    <col style="width: 10%">
                                    <col style="width: 10%">
                                    <col style="width: 15%">
                                    <col style="width: 15%">
                                </colgroup>
                                <tbody>
                                    @foreach ($materia['Composicoes'] as $comp)
                                        <tr>
                                            <td class="px-2 py-1">{{ $comp['CodigoAlternativo'] }}</td>
                                            <td class="px-2 py-1">{{ $comp['Descricao'] }}</td>
                                            <td class="px-2 py-1">{{ $comp['Unidade'] }}</td>
                                            <td class="px-2 py-1">{{ $comp['Quantidade'] }}</td>
                                            <td class="px-2 py-1"></td>
                                            <td class="px-2 py-1"></td>
                                            <td class="px-2 py-1"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-3 text-xs text-white italic">
                            Nenhum componente cadastrado.
                        </div>
                    @endif
                @endscope

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

            <div class="flex flex-row mt-4 justify-center space-x-8">
                <div class="shadow-lg shadow-blue-500/50 rounded-md px-4 py-2">
                    <x-input label="Custo material direto" wire:model.live="custoMaterialDireto" readonly/>
                </div>
                <div class="shadow-lg shadow-blue-500/50 rounded-md px-4 py-2">
                    <x-input label="Peso total" wire:model.live="pesoTotal" readonly/>
                </div>
                <div class="shadow-lg shadow-blue-500/50 rounded-md px-4 py-2">
                    <x-input label="Rendimento (UND)" wire:model.live="rendimento" readonly/>
                    <div class="text-xs pt-2">
                        Custo: R$ {{ $custoPorUnidade }} / UND
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.modal = false"/>
                <x-button wire:click="salvar" label="Salvar" form="salvar-materias-form" class="bg-blue-600 text-white"/>
            </x-slot:actions>
        </x-form>
    @endif

</x-modal>
