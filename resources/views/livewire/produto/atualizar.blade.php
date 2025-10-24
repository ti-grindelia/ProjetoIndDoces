<x-modal wire:model="modal" title="Atualizar produto" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="atualizar-produto-form">
        <x-input label="Código Alternativo" wire:model="form.codigoAlternativo"/>
        <x-input label="Descrição" wire:model="form.descricao"/>
        <x-textarea label="Descritivo" wire:model="form.descritivo" rows="3"/>
        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-produto-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
