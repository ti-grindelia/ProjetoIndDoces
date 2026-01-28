<div class="p-4 max-h-[800px] overflow-y-auto">

    <table class="min-w-full text-left text-sm">

        <thead class="text-gray-400 border-b border-gray-700">
            <tr>
                <th class="py-1 pr-4">Código</th>
                <th class="py-1 pr-4">Descrição</th>
                <th class="py-1 pr-4 text-right" colspan="2">Quantidade</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-800">

            @foreach($materias as $m)

                <tr class="text-base font-semibold text-md">
                    <td class="py-2">{{ $m['CodigoAlternativo'] }}</td>
                    <td class="py-2">{{ $m['Descricao'] ?? '' }}</td>
                    <td class="py-2 text-right">{{ $m['Quantidade'] ?? '' }}</td>
                    <td class="py-2 text-right text-sm font-normal">{{ $m['Unidade'] ?? '' }}</td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>
