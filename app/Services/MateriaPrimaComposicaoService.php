<?php

namespace App\Services;

use App\Models\MateriaPrima;
use App\Models\MateriaPrimaComposicao;
use Illuminate\Support\Collection;

class MateriaPrimaComposicaoService
{
    public function carregarComposicoes(MateriaPrima $base): array
    {
        return MateriaPrimaComposicao::query()
            ->where('MateriaPrimaBaseID', $base->MateriaPrimaID)
            ->with('materiaFilha')
            ->get()
            ->map(fn($relacao) => [
                'MateriaPrimaID' => $relacao->MateriaPrimaFilhaID,
                'CodigoAlternativo' => $relacao->materiaFilha->CodigoAlternativo ?? '',
                'Descricao' => $relacao->materiaFilha->Descricao ?? '',
                'Unidade' => $relacao->materiaFilha->Unidade ?? '',
                'Quantidade' => $relacao->Quantidade,
                'CustoUnitario' => $relacao->CustoUnitario,
                'Custo' => number_format($relacao->CustoTotal, 2),
            ])
            ->toArray();
    }

    public function pesquisar(?string $valor = null): Collection
    {
        return MateriaPrima::query()
            ->when($valor, fn($q) => $q->where('Descricao', 'like', "%$valor%"))
            ->where('Ativo', true)
            ->orderBy('Descricao')
            ->get()
            ->map(fn($m) => [
                'id' => $m->MateriaPrimaID,
                'name' => "$m->Descricao - $m->Unidade",
            ]);
    }

    public function adicionar(MateriaPrima $materia, float $quantidade): array
    {
        $custoTotal = $materia->PrecoCompra * $quantidade;

        return [
            'MateriaPrimaID' => $materia->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo,
            'Nome' => $materia->Descricao,
            'Unidade' => $materia->Unidade,
            'Quantidade' => $quantidade,
            'CustoUnitario' => $materia->PrecoCompra,
            'Custo' => number_format($custoTotal, 2),
        ];
    }

    public function salvarComposicoes(MateriaPrima $base, array $selecionadas): void
    {
        $custoTotal = 0;

        foreach ($selecionadas as $item) {

            $custo = $this->parseNumber((string) ($item['Custo'] ?? 0));
            $custoTotal += $custo;

            MateriaPrimaComposicao::query()->updateOrCreate(
                [
                    'MateriaPrimaBaseID' => $base->MateriaPrimaID,
                    'MateriaPrimaFilhaID' => $item['MateriaPrimaID'],
                ],
                [
                    'Quantidade' => $item['Quantidade'],
                    'CustoUnitario' => $item['CustoUnitario'],
                    'CustoTotal' => $custo,
                ]
            );
        }

        $rendimento = $base->Rendimento ?: 1;
        $precoCompraBase = $rendimento > 0 ? $custoTotal / $rendimento : $custoTotal;

        $base->update(['PrecoCompra' => $precoCompraBase]);
    }

    public function parseNumber(string $value): float
    {
        $s = preg_replace('/[^\d.,\-]/', '', trim($value));

        if ($s === '' || $s === null) {
            return 0.0;
        }

        $contPonto = substr_count($s, '.');
        $contVirg = substr_count($s, ',');

        if ($contPonto > 0 && $contVirg > 0) {
            $pPonto = strpos($s, '.');
            $pVirg = strpos($s, ',');

            if ($pVirg < $pPonto) {
                $s = str_replace(',', '', $s);
            } else {
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            }
        } elseif ($contVirg > 0) {
            $s = str_replace(',', '.', $s);
        }

        return (float) $s;
    }
}
