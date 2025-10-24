<x-modal wire:model="modal" title="Novo Usuário" separator class="backdrop-blur">
    <x-form wire:submit="salvar" id="criar-usuario-form">
        <x-input label="Nome" wire:model="form.nome"/>
        <x-input label="Usuário" wire:model="form.usuarioNome"/>
        <x-input label="E-mail" type="email" wire:model="form.email"/>
        <x-input label="Senha" type="password" wire:model="form.senha"/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" class="bg-blue-600 text-white" form="criar-usuario-form"/>
        </x-slot:actions>
    </x-form>
</x-modal>
