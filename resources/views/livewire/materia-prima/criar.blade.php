<x-modal wire:model="modal" title="Nova matéria-prima" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="criar-materia-form">
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
            <x-input label="Preço Compra" wire:model="form.precoCompra"/>
        </div>

        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="criar-materia-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
