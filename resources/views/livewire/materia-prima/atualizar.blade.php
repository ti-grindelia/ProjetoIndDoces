<x-modal wire:model="modal" title="Atualizar matéria-prima" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="atualizar-materia-form">
        <x-input label="Nome" wire:model="form.nome"/>
        <x-input label="Descrição" wire:model="form.descricao"/>
        <x-input label="Fornecedor" wire:model="form.fornecedor"/>
        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-materia-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
