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

class PedidoProdutosExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
{
    protected array $linhasProduto = [];
    protected int $linhaTotal = 0;

    public function __construct(
        protected array $itens,
        protected array $totais,
        protected bool $comReceita,
    ) {}

    public function headings(): array
    {
        return [
            'Código',
            'Produto',
            'Quantidade',
            'Custo MP',
            'Custo Ind.',
            'Custo Total',
            'MVA %',
            'Valor MVA',
            'ICMS %',
            'Valor ICMS',
            'Custo Total Item',
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->itens as $item) {

            $produto = $item['Produto'];

            $rows[] = [
                $produto['CodigoAlternativo'],
                $produto['Descricao'],
                round($item['Quantidade'], 2),
                round($produto['CustoMateriaPrima'], 2),
                round($produto['CustoIndustrializacao'], 2),
                round($produto['CustoTotal'], 2),
                round($produto['MVAPercentual'], 2),
                round($produto['ValorMVA'], 2),
                round($produto['ICMSPercentual'], 2),
                round($produto['ValorICMS'], 2),
                round($item['CustoTotal'], 2),
            ];

            // Guarda a linha real do produto no Excel
            $this->linhasProduto[] = count($rows) + 1;

            if ($this->comReceita && !empty($item['MateriasPrimas'])) {

                foreach ($item['MateriasPrimas'] as $mp) {

                    $rows[] = [
                        '↳ ' . $mp['CodigoAlternativo'],
                        $mp['Descricao'],
                        round($mp['Quantidade'], 3) . ' ' . $mp['Unidade'],
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ];
                }
            }
        }

        // Linha real do TOTAL
        $this->linhaTotal = count($rows) + 2;

        $rows[] = [
            '',
            'TOTAIS',
            round($this->totais['qtde'], 2),
            round($this->totais['custoMP'], 2),
            round($this->totais['custoInd'], 2),
            round($this->totais['custoTotal'], 2),
            '',
            round($this->totais['valorMva'], 2),
            '',
            round($this->totais['valorIcms'], 2),
            round($this->totais['custo'], 2),
        ];

        return $rows;
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        // Alinhamento vertical geral
        $sheet->getStyle('A:K')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Colunas numéricas alinhadas à direita
        $sheet->getStyle('C:K')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Formatação numérica
        $sheet->getStyle('C:K')
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $styles = [
            // Cabeçalho
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
        ];

        // Linhas dos produtos
        foreach ($this->linhasProduto as $linha) {
            $styles[$linha] = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D9E2F3'],
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D0D0D0'],
                    ],
                ],
            ];
        }

        // Linha de total
        $styles[$this->linhaTotal] = [
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
        ];

        return $styles;
    }
}
