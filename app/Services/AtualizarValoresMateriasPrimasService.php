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

        Excel::import(new class implements OnEachRow, WithChunkReading {

            public function onRow(Row $row): void
            {
                $linha = $row->toArray();

                $codRef = preg_replace('/[^0-9]/', '', (string) ($linha[0] ?? ''));
                $custo  = trim((string) ($linha[2] ?? ''));

                if (!$codRef || !$custo) {
                    return;
                }

                $valor = str_replace('.', '', $custo);
                $valor = str_replace(',', '.', $valor);

                dd($codRef);

                $materiaPrima = MateriaPrima::where('CodigoAlternativo', $codRef)->first();

                if (!$materiaPrima) {
                    Log::warning("Matéria não encontrada: {$codRef}");
                    return;
                }

                $materiaPrima->update([
                    'PrecoCompra' => (float) $valor
                ]);

                Log::info("Atualizado {$codRef} => {$valor}");
            }

            public function chunkSize(): int
            {
                return 200;
            }
        }, $arquivoPath);
    }
}
