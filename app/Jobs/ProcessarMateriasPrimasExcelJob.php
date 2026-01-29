<?php

namespace App\Jobs;

use App\Events\ImportacaoValoresMateriasPrimasFinalizada;
use App\Services\AtualizarValoresMateriasPrimasService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProcessarMateriasPrimasExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $arquivoPath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $arquivoPath)
    {
        $this->arquivoPath = $arquivoPath;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        if (!file_exists($this->arquivoPath)) {
            \Illuminate\Log\log()->error("Arquivo não encontrado: $this->arquivoPath");
            return;
        }

        new AtualizarValoresMateriasPrimasService()->processar($this->arquivoPath);

        Cache::put('importacao_mp_finalizada', true, now()->addMinutes(10));
    }
}
