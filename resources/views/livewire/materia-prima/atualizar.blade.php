<x-modal wire:model="modal" title="Atualizar matéria-prima" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="atualizar-materia-form">
        <div class="flex flex-row space-x-4 mb-4">
            <x-input label="Cod. Alternativo" wire:model="form.codigoAlternativo"/>
            <div class="w-2/3">
                <x-input label="Descrição" wire:model="form.descricao"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-4">
            <div class="w-1/3">
                <x-select
                    label="Unidade"
                    wire:model="form.unidade"
                    :options="[['id'=>'KG','name'=>'Quilograma'],['id'=>'L','name'=>'Litro'],['id'=>'UND','name'=>'Unidade']]"
                    placeholder="Un. medida"
                />
            </div>
            <div class="flex-1">
                <x-input label="Preço Compra" wire:model="form.precoCompra" type="number" step="0.01"/>
            </div>
        </div>

        <x-checkbox label="Permite Composição" wire:model.live="form.permiteComposicao" class="checkbox-info" tight/>
        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        @if ($form->permiteComposicao)
            <div class="mb-4">
                <x-input
                    label="Rendimento (KG, UND ou LT)"
                    wire:model="form.rendimento"
                    step="0.001"
                    placeholder="Ex: 0.125"
                />
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-materia-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
