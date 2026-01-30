<div>
    <x-header title="Empresas" separator/>

    <div class="flex justify-between mb-4 items-end">
        <div class="w-full flex space-x-4 items-end">
            <div class="w-1/3">
                <x-input
                    icon="o-magnifying-glass"
                    wire:model.live="pesquisa"
                    placeholder="Pesquise por cnpj, nome ou telefone"
                />
            </div>
            <x-select
                wire:model.live="porPagina"
                :options="[['id'=>5,'name'=>'5'],['id'=>15,'name'=>'15'],['id'=>25,'name'=>'25'],['id'=>50,'name'=>'50']]"
                placeholder="Registros por página"
            />
            <x-checkbox
                label="Mostrar empresas inativas"
                wire:model.live="pesquisaInativos"
                class="checkbox-info"
                right tight
            />
        </div>

        <x-button @click="$dispatch('empresa::criar')" label="Nova empresa" icon="o-plus" class="bg-blue-600 text-white"/>
    </div>

    <x-table :headers="$this->cabecalhos" :rows="$this->itens">
        @scope('header_CNPJ', $headers)
        <x-tabela.th :$headers nome="cnpj"/>
        @endscope

        @scope('header_RazaoSocial', $headers)
        <x-tabela.th :$headers nome="razaoSocial"/>
        @endscope

        @scope('header_Telefone', $headers)
        <x-tabela.th :$headers nome="telefone"/>
        @endscope

        @scope('header_Email', $headers)
        <x-tabela.th :$headers nome="email"/>
        @endscope

        @scope('header_EnderecoCompleto', $headers)
        <x-tabela.th :$headers nome="Endereço"/>
        @endscope

        @scope('actions', $empresa)
        <div class="flex items-center space-x-2">
            <x-button
                id="editar-btn-{{ $empresa->EmpresaID }}"
                wire:key="editar-btn-{{ $empresa->EmpresaID }}"
                icon="o-pencil"
                @click="$dispatch('empresa::atualizar', { id: {{ $empresa->EmpresaID }} })"
                spinner class="btn-sm btn-primary btn-soft"
            />

            @unless($empresa->Ativo == false)
                <x-button
                    id="arquivar-btn-{{ $empresa->EmpresaID }}"
                    wire:key="arquivar-btn-{{ $empresa->EmpresaID }}"
                    icon="o-trash"
                    @click="$dispatch('empresa::arquivar', { id: {{ $empresa->EmpresaID }} })"
                    spinner class="btn-sm btn-error btn-soft"
                />
            @else
                <x-button
                    id="restaurar-btn-{{ $empresa->EmpresaID }}"
                    wire:key="restaurar-btn-{{ $empresa->EmpresaID }}"
                    icon="o-arrow-uturn-left"
                    @click="$dispatch('empresa::restaurar', { id: {{ $empresa->EmpresaID }} })"
                    spinner class="btn-sm btn-warning btn-soft"
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->itens->links() }}

    <livewire:empresa.criar/>
    <livewire:empresa.atualizar/>
    <livewire:empresa.arquivar/>
    <livewire:empresa.restaurar/>
</div>
