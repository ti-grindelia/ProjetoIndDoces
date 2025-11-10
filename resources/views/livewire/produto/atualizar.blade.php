<x-modal wire:model="modal" title="Atualizar produto" separator class="backdrop-blur" box-class="min-w-2xl">
    <x-form wire:submit="salvar" id="atualizar-produto-form">
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/3">
                <x-input label="Código Alternativo" wire:model="form.codigoAlternativo" readonly/>
            </div>
            <div class="flex-1">
                <x-input label="Descrição" wire:model="form.descricao" readonly/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/2">
                <x-input label="Descritivo" wire:model="form.descritivo" readonly/>
            </div>
            <div class="flex-1">
                <x-input label="Categoria" wire:model="form.categoria" readonly/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/2">
                <x-input label="Preço" wire:model="form.preco" readonly/>
            </div>

            <div class="flex-1">
                <x-input label="Custo Médio" wire:model="form.custoMedio"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/2">
                <x-input label="Peso UND." wire:model="form.pesoUnidade"/>
            </div>
            <div class="flex-1">
                <x-select class="mb-2" icon="o-home-modern" :options="$this->empresas" wire:model="form.empresa"
                    label="Empresa" placeholder="Selecione a empresa"/>
            </div>
        </div>

        <x-checkbox label="Fracionado" wire:model="form.fracionado" class="checkbox-info" tight disabled/>
        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight disabled/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" form="atualizar-produto-form" class="bg-blue-600 text-white"/>
        </x-slot:actions>
    </x-form>
</x-modal>
