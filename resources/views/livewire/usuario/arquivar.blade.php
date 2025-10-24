<x-modal wire:model="modal" title="Confirmar exclusão" class="backdrop-blur"
         subtitle="Você está excluindo o usuário {{ $usuario?->Nome }}">
    <x-slot:actions>
        <x-button label="Cancelar" @click="$wire.modal = false"/>
        <x-button label="Confirmar" class="bg-blue-600 text-white" wire:click="arquivar"/>
    </x-slot:actions>
</x-modal>
