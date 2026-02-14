<x-modal wire:model="modal" title="Impressões" subtitle="Selecione a impressão desejada:" separator class="backdrop-blur" box-class="min-w-2xl">
    <x-form id="impressoes-pedido-form" wire:submit="imprimir">
        @php
            $impressoes = [
                ['id' => 'materiasPrimas', 'name' => 'Matérias-primas', 'hint' => 'Sem dados fiscais'],
                ['id' => 'produtosComReceita', 'name' => 'Produtos com receita', 'hint' => 'Com dados fiscais'],
                ['id' => 'produtosSemReceita', 'name' => 'Produtos sem receita', 'hint' => 'Com dados fiscais'],
                ['id' => 'produtosSimples', 'name' => 'Produtos simples', 'hint' => 'Sem dados fiscais'],
            ];
        @endphp

        <x-radio wire:model="impressao" :options="$impressoes"/>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.modal = false"/>
            <x-button label="Imprimir" type="submit" class="bg-blue-600 text-white" form="impressoes-pedido-form" spinner="imprimir"/>
        </x-slot:actions>
    </x-form>
</x-modal>

<script>
    window.addEventListener('abrirNovaGuia', event => {
        window.open(event.detail.url, '_blank')
    });
</script>
