<x-modal wire:model="modal" title="Atualizar Valor Industrialização" subtitle="A porcentagem atual é de {{ $porcentagemIndustrializacao }}%" separator class="backdrop-blur">
    <x-toast />

    <x-form wire:submit="salvar" id="atualizar-industrializacao-form">
        <x-input label="Insira a nova porcentagem" wire:model="novaPorcentagemIndustrializacao" suffix="%" type="number"/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-industrializacao-form" class="bg-blue-600 text-white" spinner="salvar"/>
        </x-slot:actions>
    </x-form>
</x-modal>
