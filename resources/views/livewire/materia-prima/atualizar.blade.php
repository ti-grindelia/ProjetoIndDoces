<x-modal wire:model="modal" title="Atualizar matéria-prima" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="atualizar-materia-form">
        <div class="flex flex-row space-x-4 mb-4">
            <x-input label="Cod. Alternativo" wire:model="form.codigoAlternativo"/>
            <div class="w-2/3">
                <x-input label="Nome" wire:model="form.nome"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-4">
            <div class="w-1/3">
                <x-select
                    label="Unidade"
                    wire:model="form.unidade"
                    :options="[['id'=>'KG','name'=>'Quilograma'],['id'=>'L','name'=>'Litro']]"
                    placeholder="Un. medida"
                />
            </div>
            <x-input label="Valor" wire:model="form.valor"/>
            <x-input label="Custo Médio" wire:model="form.custoMedio"/>
        </div>
        
        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-materia-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
