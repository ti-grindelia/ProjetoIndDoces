<div>
    <x-header title="Usuários" separator/>

    <div class="flex justify-between mb-4 items-end">
        <div class="w-full flex space-x-4 items-end">
            <div class="w-1/3">
                <x-input
                    icon="o-magnifying-glass"
                    wire:model.live="pesquisa"
                    placeholder="Pesquise por nome, usuário ou e-mail"
                />
            </div>
            <x-select
                wire:model.live="porPagina"
                :options="[['id'=>5,'name'=>'5'],['id'=>15,'name'=>'15'],['id'=>25,'name'=>'25'],['id'=>50,'name'=>'50']]"
                placeholder="Registros por página"
            />
            <x-checkbox
                label="Mostrar usuários inativos"
                wire:model.live="pesquisaInativos"
                class="checkbox-info"
                right tight
            />
        </div>

        <x-button @click="$dispatch('usuario::criar')" label="Novo usuário" icon="o-plus" class="bg-blue-600 text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_UsuarioID', $headers)
        <x-tabela.th :$headers nome="usuarioID"/>
        @endscope

        @scope('header_Nome', $headers)
        <x-tabela.th :$headers nome="nome"/>
        @endscope

        @scope('header_Usuario', $headers)
        <x-tabela.th :$headers nome="usuario"/>
        @endscope

        @scope('header_Email', $headers)
        <x-tabela.th :$headers nome="email"/>
        @endscope

        @scope('actions', $usuario)
        <div class="flex items-center space-x-2">
            <x-button
                id="editar-btn-{{ $usuario->UsuarioID }}"
                wire:key="editar-btn-{{ $usuario->UsuarioID }}"
                icon="o-pencil"
                @click="$dispatch('usuario::atualizar', { id: {{ $usuario->UsuarioID }} })"
                spinner class="btn-sm btn-primary"
            />

            @unless($usuario->Ativo == false)
                <x-button
                    id="arquivar-btn-{{ $usuario->UsuarioID }}"
                    wire:key="arquivar-btn-{{ $usuario->UsuarioID }}"
                    icon="o-trash"
                    @click="$dispatch('usuario::arquivar', { id: {{ $usuario->UsuarioID }} })"
                    spinner class="btn-sm btn-error"
                />
            @else
                <x-button
                    id="restaurar-btn-{{ $usuario->UsuarioID }}"
                    wire:key="restaurar-btn-{{ $usuario->UsuarioID }}"
                    icon="o-arrow-uturn-left"
                    @click="$dispatch('usuario::restaurar', { id: {{ $usuario->UsuarioID }} })"
                    spinner class="btn-sm btn-warning"
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

{{--    <livewire:usuario.criar/>--}}
{{--    <livewire:usuario.atualizar/>--}}
{{--    <livewire:usuario.arquivar/>--}}
{{--    <livewire:usuario.restaurar/>--}}
</div>
