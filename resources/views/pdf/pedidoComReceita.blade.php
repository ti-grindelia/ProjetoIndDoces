<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Cópia Pedido</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .empresa {
            font-size: 18px;
            font-weight: bold;
        }

        .sub {
            font-size: 11px;
            color: #666;
        }

        .right {
            text-align: right;
        }

        .label {
            font-size: 11px;
            color: #777;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #e5e7eb;
            text-align: left;
            padding: 5px;
            border: 1px solid #d1d5db;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 5px;
            border: 1px solid #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .linha-produto {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .linha-produto td {
            border-bottom: 1px dashed #cbd5f5;
        }

        .linha-materia td {
            border: none !important;
            font-size: 8px;
            color: #555;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .linha-materia td:first-child {
            padding-left: 20px;
        }

        .total-produto td {
            background: #fafafa;
            font-weight: bold;
            font-size: 9px;
            border-top: 1px dashed #cbd5f5;
        }

        .separador td {
            border: none;
            height: 6px;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="empresa">DBR | SBR</div>
                    <div class="sub">Indústria de Doces</div>
                </td>

                <td class="right">
                    <div class="label">Pedido</div>
                    <div class="value">#{{ $pedido->PedidoID }}</div>
                </td>
            </tr>

            <tr>
                <td style="padding-top: 8px;">
                    <span class="label">Usuário:</span>
                    <span class="value">{{ $user->Nome ?? '-' }}</span>
                </td>

                <td class="right" style="padding-top: 8px;">
                    <span class="label">Data:</span>
                    <span class="value">
                        {{ optional($pedido->DataInclusao)->format('d/m/Y') ?? '-' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- TABELA PRODUTOS --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th class="text-right">Qtde</th>
                <th class="text-right">C.MP</th>
                <th class="text-right">C.Ind</th>
                <th class="text-right">C.Tot</th>
                <th class="text-right">MVA%</th>
                <th class="text-right">V.MVA</th>
                <th class="text-right">ICMS%</th>
                <th class="text-right">V.ICMS</th>
                <th class="text-right">Custo</th>
            </tr>
        </thead>

        <tbody>
            @foreach($produtos as $item)
                {{-- PRODUTO --}}
                <tr class="linha-produto">
                    <td>{{ $item['Produto']['CodigoAlternativo'] }}</td>
                    <td>{{ $item['Produto']['Descricao'] }}</td>
                    <td class="text-right">
                        {{ number_format($item['Quantidade'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['CustoMateriaPrima'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['CustoIndustrializacao'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['CustoTotal'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ $item['Produto']['MVAPercentual'] }}%
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['ValorMVA'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ $item['Produto']['ICMSPercentual'] }}%
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['ValorICMS'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item['CustoTotal'], 2, ',', '.') }}
                    </td>
                </tr>

                {{-- MATÉRIAS-PRIMAS --}}
                @foreach($item['MateriasPrimas'] as $mp)
                    <tr class="linha-materia">
                        <td>{{ $mp['CodigoAlternativo'] }}</td>
                        <td>{{ $mp['Descricao'] }}</td>
                        <td class="text-right">
                            {{ number_format($mp['Quantidade'], 3, ',', '.') }} {{ $mp['Unidade'] }}
                        </td>
                        <td colspan="8"></td>
                    </tr>
                @endforeach

                <tr class="separador">
                    <td colspan="11"></td>
                </tr>

            @endforeach

            <tr class="total-produto">
                <td colspan="2" class="text-center">
                    TOTAL
                </td>
                <td class="text-right">
                    {{ number_format($totais['qtde'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($totais['custoMP'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($totais['custoInd'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($totais['custoTotal'], 2, ',', '.') }}
                </td>
                <td class="text-center">
                    -
                </td>
                <td class="text-right">
                    {{ number_format($totais['valorMva'], 2, ',', '.') }}
                </td>
                <td class="text-center">
                    -
                </td>
                <td class="text-right">
                    {{ number_format($totais['valorIcms'], 2, ',', '.') }}
                </td>
                <td class="text-right">
                    {{ number_format($totais['custo'], 2, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
