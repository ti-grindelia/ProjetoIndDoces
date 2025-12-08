<x-modal
        wire:model="modal"
        title="Confirmar Cancelamento"
        subtitle="Você está cancelando o pedido {{ $pedido?->PedidoID }} da empresa {{ $pedido?->empresa?->RazaoSocial }}"
        class="backdrop-blur">

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Confirmar" class="btn-primary" wire:click="cancelar"/>
        </x-slot:actions>
    </x-modal>
