<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MateriaPrimasExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
{
    public function __construct(
        protected array $materiasPrimas,
        protected string $pedidoID,
    ) {}

    public function headings(): array
    {
        return [
            'Código',
            'Descrição',
            'Valor',
            'Quantidade',
            'Unidade',
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->materiasPrimas as $mp) {
            $rows[] = [
                $mp['CodigoAlternativo'],
                $mp['Descricao'],
                round((float) $mp['PrecoCompra'], 2),
                round((float) $mp['Quantidade'], 3),
                $mp['Unidade'],
            ];
        }

        return $rows;
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}
