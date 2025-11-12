<div class="ml-6 border-l-2 border-gray-600 pl-4 text-sm">
    <table class="min-w-full border-separate border-spacing-y-1 text-left text-sm text-gray-600">
        <thead class="text-gray-700">
            <tr>
                <th class="py-1 pr-4">Descrição</th>
                <th class="py-1 pr-4">Quantidade UND.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $m)
                <tr>
                    <td class="py-1">{{ $m['materia_prima']['Descricao'] ?? '' }}</td>
                    <td class="py-1">{{ $m['Quantidade'] ?? '' }} {{ $m['materia_prima']['Unidade'] ?? '' }}</td>
                </tr>

                {{-- Se tiver composição --}}
                @php
                    $composicao = $m['materia_prima']['componentes'] ?? [];
                @endphp

                @if(!empty($composicao))
                    <tr>
                        <td colspan="3" class="py-1 pl-6">
                            <div class="border-l-2 border-gray-400 pl-4">
                                {{-- Tabela dos componentes --}}
                                <table class="min-w-full border-separate border-spacing-y-1 text-left mt-1 text-xs text-gray-400">
                                    <colgroup>
                                        <col style="width: 62%">
                                        <col style="width: 37%">
                                    </colgroup>
                                    <tbody>
                                        @foreach($composicao as $c)
                                            <tr>
                                                <td class="py-0.5">{{ $c['Descricao'] ?? '' }}</td>
                                                <td class="py-0.5">{{ $c['pivot']['Quantidade'] ?? '' }} {{ $c['Unidade'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
