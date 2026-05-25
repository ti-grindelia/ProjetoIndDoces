<?php

namespace App\Services;

use App\Models\MateriaPrima;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Facades\Excel;

class AtualizarValoresMateriasPrimasService
{
    /**
     * @throws Exception
     */
    public function processar(string $arquivoPath): void
    {
        Log::info("Processando arquivo: $arquivoPath");

        if (!file_exists($arquivoPath)) {
            throw new Exception("Arquivo não encontrado: $arquivoPath");
        }

        $materias = MateriaPrima::query()
            ->get()
            ->keyBy('CodigoAlternativo');

        Excel::import(new class($materias) implements OnEachRow, WithChunkReading {
            private $materias;

            public function __construct($materias)
            {
                $this->materias = $materias;
            }

            public function onRow(Row $row): void
            {
                $linha = $row->toArray();

                $codRef = trim((string) ($linha[0] ?? ''));
                $custo  = $linha[2] ?? null;

                if (!$codRef || !$custo) {
                    return;
                }

                $materiaPrima = $this->materias[$codRef] ?? null;

                if (!$materiaPrima) {
                    return;
                }

                $materiaPrima->PrecoCompra = (float) str_replace(',', '.', $custo);

                $materiaPrima->save();
            }

            public function chunkSize(): int
            {
                return 200;
            }
        }, $arquivoPath);
    }
}
