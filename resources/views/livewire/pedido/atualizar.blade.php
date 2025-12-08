<x-modal wire:model="modal" title="Pedido #{{ $form->pedidoID ?? '' }}" separator class="backdrop-blur" box-class="min-w-2xl">
    <x-form id="atualizar-pedido-form">
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/2">
                <x-input label="Empresa" wire:model="form.empresa" readonly/>
            </div>
            <div class="flex-1">
                <x-input label="Usuário" wire:model="form.usuario" readonly/>
            </div>
        </div>
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/2">
                <x-datetime label="DataInclusao" wire:model="form.dataInclusao" readonly/>
            </div>
            <div class="flex-1">
                <x-input label="Custo Total" wire:model="form.custoTotal" readonly/>
            </div>
        </div>

        <div class="mb-2">
            <x-input label="Status" wire:model="form.status" readonly>
                <x-slot:append>
                    <x-button label="proximo"
                              class="join-item btn-primary"
                              :label="$this->statusButtonLabel"
                              :disabled="$this->statusButtonDisabled"
                    />
                </x-slot:append>
            </x-input>
        </div>

        <x-card>
            <div x-data="{ aba: 'itens' }">
                <div class="flex border-b border-gray-300 mb-4">
                    <button
                        type="button"
                        class="px-4 py-2 text-md font-semibold cursor-pointer"
                        :class="aba === 'itens'
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-500 hover:text-gray-700'"
                        @click="aba = 'itens'"
                    >
                        Itens
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 text-md font-semibold ml-4 cursor-pointer"
                        :class="aba === 'materias-primas'
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-500 hover:text-gray-700'"
                        @click="aba = 'materias-primas'"
                    >
                        Matérias-primas
                    </button>
                </div>
                <div x-show="aba === 'itens'" class="max-h-[500px] overflow-y-auto">
                    @foreach($form->itens as $item)
                        <div class="mb-4 border-b border-gray-500 pb-3">
                            <div class="font-semibold text-sm flex flex-row justify-between">
                                <div>{{ $item['Produto']['Descricao'] }}</div>
                                <div>{{ $item['Quantidade'] }}</div>
                            </div>

                            <div class="mt-2 ml-4">
                                @foreach($item['MateriasPrimas'] as $mp)
                                    <div class="text-xs ml-2 flex flex-row justify-between">
                                        <div>• {{ $mp['Descricao'] }}</div>
                                        <div>{{ $mp['Quantidade'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div x-show="aba === 'materias-primas'" class="max-h-[500px] overflow-y-auto">

                </div>
            </div>
        </x-card>

        @if($form->alteradoEm)
            <div class="flex flex-row space-x-4 mb-2 w-full">
                <div class="w-1/2">
                    <x-input label="Alterado Em" wire:model="form.alteradoEm" readonly/>
                </div>
                <div class="flex-1">
                    <x-input label="Alterado Por" wire:model="form.alteradoPor" readonly/>
                </div>
            </div>
        @endif

        @if($form->canceladoEm)
            <div class="flex flex-row space-x-4 mb-2 w-full">
                <div class="w-1/2">
                    <x-input label="Cancelado Em" wire:model="form.canceladoEm" readonly/>
                </div>
                <div class="flex-1">
                    <x-input label="Cancelado Por" wire:model="form.canceladoPor" readonly/>
                </div>
            </div>
        @endif
    </x-form>
</x-modal>
