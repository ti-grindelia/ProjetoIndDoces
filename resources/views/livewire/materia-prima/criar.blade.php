<x-modal wire:model="modal" title="Nova matéria-prima" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="criar-materia-form">
        <x-input label="Nome" wire:model="form.nome"/>
        <x-input label="Descrição" wire:model="form.descricao"/>
        <x-input label="Fornecedor" wire:model="form.fornecedor"/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="criar-materia-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
