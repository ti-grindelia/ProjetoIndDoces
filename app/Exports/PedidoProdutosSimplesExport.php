<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PedidoProdutosSimplesExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
{
    protected int $linhaTotal = 0;

    public function __construct(
        protected array $itens,
        protected array $totais,
    ) {}

    public function headings(): array
    {
        return [
            'Código',
            'Produto',
            'Quantidade',
            'Custo Ind.',
            'Custo Total Ind.',
        ];
    }

    public function array(): array
    {
        $rows = array_map(fn ($item) => [
            $item['Produto']['CodigoAlternativo'],
            $item['Produto']['Descricao'],
            round($item['Quantidade'], 2),
            round($item['Produto']['CustoIndustrializacao'], 2),
            round(
                $item['Produto']['CustoIndustrializacao'] * $item['Quantidade'],
                2
            ),
        ], $this->itens);

        $this->linhaTotal = count($rows) + 2;

        $rows[] = [
            '',
            'TOTAIS',
            round($this->totais['qtde'], 2),
            round($this->totais['custoInd'], 2),
            '',
        ];

        return $rows;
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        $sheet->getStyle('A:E')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('C:E')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('C:E')
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],

            $this->linhaTotal => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'FFF2CC'],
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '808080'],
                    ],
                ],
            ],
        ];
    }
}
