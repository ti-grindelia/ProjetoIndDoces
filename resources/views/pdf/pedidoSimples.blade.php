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

        .total-produto td {
            background: #e4e6eb;
            font-weight: bold;
            font-size: 10px;
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
                <th class="text-right">C.Ind</th>
            </tr>
        </thead>

        <tbody>
            @foreach($produtos as $item)
                {{-- PRODUTO --}}
                <tr>
                    <td>{{ $item['Produto']['CodigoAlternativo'] }}</td>
                    <td>{{ $item['Produto']['Descricao'] }}</td>
                    <td class="text-right">
                        {{ number_format($item['Quantidade'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item['Produto']['CustoIndustrializacao'], 2, ',', '.') }}
                    </td>
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
                    {{ number_format($totais['custoInd'], 2, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
