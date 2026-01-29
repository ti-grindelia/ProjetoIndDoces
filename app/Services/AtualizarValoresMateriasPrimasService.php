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

        Excel::import(new class implements OnEachRow, WithChunkReading {
            public function onRow(Row $row): void
            {
                $linha = $row->toArray();

                $codRef = $linha[0] ?? null;
                $custo  = $linha[2] ?? null;

                if (!$codRef || !$custo) {
                    return;
                }

                $materiaPrima = MateriaPrima::where('CodigoAlternativo', $codRef)->first();

                if (!$materiaPrima) {
                    return;
                }

                $materiaPrima->update([
                    'PrecoCompra' => (float) str_replace(',', '.', $custo)
                ]);
            }

            public function chunkSize(): int
            {
                return 200;
            }
        }, $arquivoPath);
    }
}
