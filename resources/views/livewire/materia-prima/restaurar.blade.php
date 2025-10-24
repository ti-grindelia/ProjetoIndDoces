<x-modal wire:model="modal" title="Restaurar matéria-prima" class="backdrop-blur"
    subtitle="Você está restaurando a matéria-prima {{ $materiaPrima?->Nome }}">
    <x-slot:actions>
        <x-button label="Cancelar" @click="$wire.modal = false"/>
        <x-button label="Concluir" class="bg-blue-600 text-white" wire:click="restaurar"/>
    </x-slot:actions>
</x-modal>
