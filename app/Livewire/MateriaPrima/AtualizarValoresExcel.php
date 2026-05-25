<?php

namespace App\Livewire\MateriaPrima;

use App\Jobs\ProcessarMateriasPrimasExcelJob;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class AtualizarValoresExcel extends Component
{
    use WithFileUploads;

    use Toast;

    public bool $modal = false;

    public $arquivo = null;

    public bool $processando = false;

    public function render(): View
    {
        return view('livewire.materia-prima.atualizar-valores-excel');
    }

    #[On('materia-prima::atualizarValores')]
    public function abrir(): void
    {
        $this->modal = true;
    }

    public function processar(): void
    {
        $this->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls',
        ]);

        if (!Storage::disk('local')->exists('imports')) {
            Storage::disk('local')->makeDirectory('imports');
        }

        $nomeArquivo = time() . '_' . str_replace(' ', '_', $this->arquivo->getClientOriginalName());
        $path = Storage::disk('local')->putFileAs('imports', $this->arquivo, $nomeArquivo);

        if (!Storage::disk('local')->exists($path)) {
            \Illuminate\Log\log()->error("Arquivo não encontrado no momento de despachar: $path");
            return;
        }

        $arquivoPath = Storage::disk('local')->path($path);

        $this->processando = true;

        cache()->forget('importacao_mp_finalizada');

        ProcessarMateriasPrimasExcelJob::dispatch($arquivoPath);
    }

    public function verificarImportacao(): void
    {
        if (!$this->processando) {
            return;
        }

        if (cache()->pull('importacao_mp_finalizada')) {
            $this->processando = false;
            $this->modal = false;

            $this->success(
                title: 'Sucesso',
                description: 'Valores atualizados com sucesso',
                timeout: 5000,
            );

            $this->reset('arquivo');
        }
    }
}
