<x-modal wire:model="modal" title="Atualizar empresa" separator class="backdrop-blur" box-class="min-w-3xl">
    <x-form wire:submit="salvar" id="atualizar-empresa-form">
        <div class="flex flex-row space-x-4 mb-2 w-full">
            <div class="w-1/3">
                <x-input label="CNPJ" wire:model="form.cnpj"
                    x-data
                    x-on:input="
                        let v = $el.value.replace(/\D/g, '');
                        if (v.length > 14) v = v.slice(0, 14);
                        if (v.length > 12) {
                            v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5');
                        } else if (v.length > 8) {
                            v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{0,4})/, '$1.$2.$3/$4');
                        } else if (v.length > 5) {
                            v = v.replace(/(\d{2})(\d{3})(\d{0,3})/, '$1.$2.$3');
                        } else if (v.length > 2) {
                            v = v.replace(/(\d{2})(\d{0,3})/, '$1.$2');
                        }
                        $el.value = v;
                    "
                />
            </div>
            <div class="flex-1">
                <x-input label="Razão Social" wire:model="form.razaoSocial" class="w-full"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2">
            <div class="w-1/3">
                <x-input label="Telefone" wire:model.lazy="form.telefone"
                    x-data
                    x-on:input="
                        let v = $el.value.replace(/\D/g, '');
                        if (v.length > 11) v = v.slice(0, 11);

                        if (v.length <= 2) {
                            v = '(' + v;
                        } else if (v.length <= 6) {
                            v = v.replace(/(\d{2})(\d{0,4})/, '($1) $2');
                        } else if (v.length <= 10) {
                            v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                        } else {
                            v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                        }
                        $el.value = v;
                    "
                />
            </div>
            <div class="flex-1">
                <x-input label="E-mail" wire:model="form.email" class="w-full"/>
            </div>
        </div>

        <div class="w-1/3">
            <x-input label="CEP" wire:model.lazy="form.cep" wire:blur="buscarCEP"
                x-data
                x-on:input="
                    let v = $el.value.replace(/\D/g, '');
                    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
                    $el.value = v;
                "
            />
        </div>

        <div class="flex flex-row space-x-4 mb-2">
            <div class="w-2/3">
                <x-input label="Endereço" wire:model="form.endereco"/>
            </div>
            <div class="flex-1">
                <x-input label="Número" wire:model="form.numero"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2">
            <div class="w-1/2">
                <x-input label="Complemento" wire:model="form.complemento"/>
            </div>
            <div class="flex-1">
                <x-input label="Bairro" wire:model="form.bairro"/>
            </div>
        </div>

        <div class="flex flex-row space-x-4 mb-2">
            <div class="w-1/2">
                <x-input label="Cidade" wire:model="form.cidade"/>
            </div>
            <div class="flex-1">
                <x-input label="Estado" wire:model="form.estado"/>
            </div>
        </div>

        <x-checkbox label="Ativo" wire:model="form.ativo" class="checkbox-info" tight/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Salvar" type="submit" class="bg-blue-600 text-white" form="atualizar-empresa-form"/>
        </x-slot:actions>
    </x-form>
</x-modal>
