<?php

namespace App\Services;

use App\Models\MateriaPrima;
use App\Models\Produto;
use App\Models\ProdutoMateriaPrima;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProdutoMateriaPrimaService
{
    public function carregarMaterias(Produto $produto): array
    {
        return ProdutoMateriaPrima::query()
            ->where('ProdutoID', $produto->ProdutoID)
            ->with(['materiaPrima.componentes'])
            ->get()
            ->map(fn($relacao) => $this->mapSelecionada($relacao))
            ->toArray();
    }

    public function pesquisarMaterias(?string $valor = null): Collection
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

    public function adicionarMateria(int $id, float $quantidade, array $lista): array
    {
        $materia = MateriaPrima::with('componentes')->find($id);
        if (!$materia) return $lista;

        $jaExiste = collect($lista)->contains('MateriaPrimaID', $materia->MateriaPrimaID);
        if ($jaExiste) return $lista;

        $lista[] = $this->mapInserida($materia, $quantidade);

        return $lista;
    }

    public function removerMateria(int $id, array $lista): array
    {
        return array_values(array_filter(
            $lista,
            fn($m) => $m['MateriaPrimaID'] !== $id
        ));
    }

    /**
     * @throws Throwable
     */
    public function salvar(Produto $produto, array $materias, float $custoTotal): void
    {
        DB::beginTransaction();

        try {
            $idsAtuais = collect($materias)
                ->pluck('MateriaPrimaID')
                ->toArray();

            ProdutoMateriaPrima::query()
                ->where('ProdutoID', $produto->ProdutoID)
                ->whereNotIn('MateriaPrimaID', $idsAtuais)
                ->delete();

            foreach ($materias as $m) {
                $custoUnitario = $this->normalizarNumero($m['CustoUnitario']);
                $custo = $this->normalizarNumero($m['Custo']);

                ProdutoMateriaPrima::query()->updateOrCreate(
                    [
                        'ProdutoID' => $produto->ProdutoID,
                        'MateriaPrimaID' => $m['MateriaPrimaID'],
                    ],
                    [
                        'Unidade' => $m['Unidade'],
                        'Quantidade' => $m['Quantidade'],
                        'CustoUnitario' => $custoUnitario,
                        'Custo' => $custo,
                    ]
                );
            }

            $produto->update(['CustoMedio' => $custoTotal]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function normalizarNumero($valor): float
    {
        if (is_null($valor)) {
            return 0.0;
        }

        if (is_int($valor) || is_float($valor)) {
            return round((float) $valor, 2);
        }

        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);

        return round((float) $valor, 2);
    }

    public function calcularTotais(array $lista, float $rendimento): array
    {
        $custoMaterialDireto = 0;
        $pesoTotal = 0;

        foreach ($lista as $m) {
            $quant = (float) $m['Quantidade'];
            $unit = (float) $m['CustoUnitario'];

            $custoMaterialDireto += $quant * $unit;
            $pesoTotal += $quant;
        }

        $custoPorUnidade = $rendimento > 0
            ? $custoMaterialDireto / $rendimento
            : 0;

        return [
            'custoMaterialDireto' => round($custoMaterialDireto, 2),
            'pesoTotal'           => round($pesoTotal, 4),
            'custoPorUnidade'     => round($custoPorUnidade, 2),
            'rendimento'          => round($rendimento, 2)
        ];
    }

    private function mapSelecionada($relacao): array
    {
        $materia = $relacao->materiaPrima;

        return [
            'MateriaPrimaID'    => $relacao->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo ?? '',
            'Descricao'         => $materia->Descricao ?? '',
            'Unidade'           => $relacao->Unidade,
            'Quantidade'        => $relacao->Quantidade,
            'CustoUnitario'     => $relacao->CustoUnitario,
            'Custo'             => number_format($relacao->Custo, 2),
            'PermiteComposicao' => $materia->PermiteComposicao ?? false,
            'Composicoes'       => $materia->PermiteComposicao
                ? $this->calcularSubcomponentesProporcionais($materia, $relacao->Quantidade)
                : [],
        ];
    }

    private function mapInserida($materia, float $quantidade): array
    {
        $custo = $materia->PrecoCompra * $quantidade;

        return [
            'MateriaPrimaID'    => $materia->MateriaPrimaID,
            'CodigoAlternativo' => $materia->CodigoAlternativo,
            'Descricao'         => $materia->Descricao,
            'Unidade'           => $materia->Unidade,
            'Quantidade'        => $quantidade,
            'CustoUnitario'     => $materia->PrecoCompra,
            'Custo'             => number_format($custo, 2),
            'PermiteComposicao' => $materia->PermiteComposicao,
            'Composicoes'       => $materia->PermiteComposicao
                ? $this->calcularSubcomponentesProporcionais($materia, $quantidade)
                : [],
        ];
    }

    private function getComposicoes($materia): array
    {
        return $materia->componentes->map(function ($comp) {
            $q = $comp->pivot->Quantidade ?? 0;
            $unit = $comp->PrecoCompra ?? 0;

            return [
                'CodigoAlternativo' => $comp->CodigoAlternativo,
                'Descricao'         => $comp->Descricao,
                'Unidade'           => $comp->Unidade,
                'Quantidade'        => $q,
                'CustoUnitario'     => $unit,
                'CustoTotal'        => $unit * $q,
            ];
        })->toArray();
    }

    private function calcularSubcomponentesProporcionais($materia, float $quantidadeUsada): array
    {
        if (!$materia->PermiteComposicao || $quantidadeUsada <= 0) return [];

        $rendimento = $materia->Rendimento ?? 1;

        $resultado = [];

        foreach ($materia->componentes as $comp) {
            $quantidadeOriginal = $comp->pivot->Quantidade;
            $quantidadeProporcional = ($quantidadeOriginal / $rendimento) * $quantidadeUsada;

            $unitario = $comp->PrecoCompra;
            $custo = $unitario * $quantidadeProporcional;

            $resultado[] = [
                'CodigoAlternativo' => $comp->CodigoAlternativo,
                'Descricao'         => $comp->Descricao,
                'Unidade'           => $comp->Unidade,
                'Quantidade'        => round($quantidadeProporcional, 3),
                'CustoUnitario'     => $unitario,
                'CustoTotal'        => $custo,
            ];
        }

        return $resultado;
    }
}
