<x-card class="py-4 mt-10">
    <div class="flex flex-row justify-start items-center gap-16">
        <label class="block text-lg font-semibold">
            Pedido #{{ $proximoPedidoID }}
        </label>
        <div class="flex flex-row gap-2 text-md font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>
            {{ $dataHoraAtual }}
        </div>
        <div class="w-1/8 text-md font-medium">
            <x-select
                :options="[
                    ['id'=>'Aberto','name'=>'Aberto'],
                    ['id'=>'Producao','name'=>'Produção','disabled'=>true],
                    ['id'=>'Finalizado','name'=>'Finalizado','disabled'=>true]
                ]"
            />
        </div>
    </div>

    <div class="pl-4 mt-6">
        <label class="block text-sm font-semibold mb-2">
            Selecionar arquivo de pedido
        </label>
        <div class="flex flex-row gap-4 justify-start items-center">
            <div class="w-1/2">
                <x-file wire:model="arquivo" accept=".xls,.xlsx" required/>
            </div>
            <div class="w-1/3">
                <x-button
                    label="Processar"
                    class="bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    wire:click="processar"
                    :disabled="!$arquivo"
                    spinner="processar"/>
            </div>
        </div>
        <div wire:loading wire:target="arquivo" class="mt-2 text-sm text-gray-500">
            Carregando arquivo...
        </div>
    </div>

    <x-card class="mx-auto p-12">
        @if($produtosProcessados)
            <div x-data="{ aba: 'doces' }" class="mt-6">
                <div class="flex border-b border-gray-300 mb-4">
                    <button
                        class="px-4 py-2 text-md font-semibold cursor-pointer"
                        :class="aba === 'doces'
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-500 hover:text-gray-700'"
                        @click="aba = 'doces'"
                    >
                        🧁 DBR
                    </button>
                    <button
                        class="px-4 py-2 text-md font-semibold ml-4 cursor-pointer"
                        :class="aba === 'salgados'
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-500 hover:text-gray-700'"
                        @click="aba = 'salgados'"
                    >
                        🥟 SBR
                    </button>
                    <button
                        class="px-4 py-2 text-md font-semibold ml-4 cursor-pointer"
                        :class="aba === 'materias-primas'
                            ? 'border-b-2 border-blue-600 text-blue-600'
                            : 'text-gray-500 hover:text-gray-700'"
                        @click="aba = 'salgados'"
                    >
                        🧪 Matérias-Primas
                    </button>
                </div>

                <div x-show="aba === 'doces'">
                    <x-pedidos.tabela-doces :produtos-industria-doces="$produtosIndustriaDoces"/>
                </div>

                <div x-show="aba === 'salgados'">
                    <x-pedidos.tabela-salgados :produtos-industria-salgados="$produtosIndustriaSalgados"/>
                </div>

                <div x-show="aba === 'materias-primas'">

                </div>

            </div>

{{--            <div class="w-full mt-10">--}}
{{--                <div class="shadow rounded-xl p-4 border border-gray-700">--}}
{{--                    <h2 class="text-lg font-bold text-blue-700 mb-4">--}}
{{--                        🧪 Materiais Totais Necessários--}}
{{--                    </h2>--}}

{{--                    <div class="max-h-[800px] overflow-y-auto pr-2">--}}
{{--                        @forelse($materiasTotais as $mp)--}}
{{--                            <div class="flex justify-between items-center border-b last:border-none py-2">--}}

{{--                                <div>--}}
{{--                                    <div class="font-medium">--}}
{{--                                        {{ $mp['Descricao'] }}--}}
{{--                                    </div>--}}

{{--                                    <span class="text-sm text-gray-500 block">--}}
{{--                                        Unidade: {{ $mp['Unidade'] }}--}}
{{--                                    </span>--}}
{{--                                </div>--}}

{{--                                <div class="text-right font-bold text-indigo-700">--}}
{{--                                    {{ number_format($mp['Total'], 3, ',', '.') }}--}}
{{--                                    {{ $mp['Unidade'] }}--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        @empty--}}
{{--                            <p class="text-gray-500 text-sm">Nenhuma matéria-prima encontrada.</p>--}}
{{--                        @endforelse--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        @endif
    </x-card>

</x-card>
