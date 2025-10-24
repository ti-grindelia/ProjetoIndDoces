<x-card title="Novo Usuário" shadow class="mx-auto w-[450px]">
    <x-form wire:submit="registrar">
        <x-input label="Nome" wire:model="nome" icon="o-user"/>
        <x-input label="Usuário" wire:model="usuario" icon="o-user-circle"/>
        <x-input label="E-mail" type="email" wire:model="email" icon="o-envelope"/>
        <x-input label="Senha" type="password" wire:model="senha" icon="o-lock-closed"/>

        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a href="{{ route('login') }}" wire:navigate class="link text-blue-600">
                    Eu já tenho uma conta
                </a>
                <div>
                    <x-button label="Limpar" type="reset"/>
                    <x-button label="Registrar" type="submit" class="bg-blue-600 text-white" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>
