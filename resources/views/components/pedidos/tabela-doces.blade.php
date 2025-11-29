<div class="p-4 max-h-[800px] overflow-y-auto">

    <table class="w-full text-sm">
        <thead class="text-gray-400 border-b border-gray-700">
            <tr>
                <th class="text-left py-2 w-20">Código</th>
                <th class="text-left py-2">Descrição</th>
                <th class="text-right py-2 w-32">Quantidade</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-800">
            @foreach ($produtosIndustriaDoces as $item)

                <tr class="text-base font-semibold text-md">
                    <td class="py-2">{{ $item['Codigo'] }}</td>
                    <td class="py-2">{{ $item['Descricao'] }}</td>
                    <td class="py-2 text-right">{{ $item['Quantidade'] }}</td>
                </tr>


                @if (!empty($item['MateriasPrimas']))
                    @foreach ($item['MateriasPrimas'] as $mp)
                        <tr class="text-gray-500">
                            <td class="py-1"></td>

                            <td class="flex items-center gap-2 pl-6 border-l border-gray-600">
                                <span class="text-xs">{{ $mp['CodigoAlternativo'] }}</span>
                                <span>• {{ $mp['Descricao'] }}</span>
                            </td>

                            <td class="py-1 text-left">
                                {{ number_format($mp['Total'], 3, '.', '.') }}
                            </td>
                        </tr>
                    @endforeach
                @endif

            @endforeach
        </tbody>
    </table>

</div>
