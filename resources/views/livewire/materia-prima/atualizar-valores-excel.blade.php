<x-modal wire:model="modal" title="Atualizar Valores" subtitle="Selecione o arquivo de atualização de valores" separator class="backdrop-blur">
    <x-toast />

    <div class="mt-6">
        <div class="flex flex-row gap-4 justify-start items-center">
            <div class="w-2/3">
                <x-file wire:model="arquivo" accept=".xls,.xlsx" required/>
            </div>
            <div class="w-1/3">
                <x-button
                    label="Processar"
                    class="bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    wire:click="processar"
                    :disabled="!$arquivo || $processando"
                    spinner="processar"/>
            </div>
        </div>

        <div wire:loading wire:target="arquivo" class="mt-2 text-sm text-gray-500">
            Carregando arquivo...
        </div>

        @if ($processando)
            <div wire:poll.2s="verificarImportacao" class="mt-2 text-sm text-gray-500">
                Processando valores...
            </div>
        @endif
    </div>
</x-modal>
