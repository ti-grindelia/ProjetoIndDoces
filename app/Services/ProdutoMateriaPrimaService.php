<?php

namespace App\Services;

use App\Models\MateriaPrima;
use App\Models\Produto;
use App\Models\ProdutoMateriaPrima;
use Illuminate\Support\Collection;

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

    public function salvar(Produto $produto, array $materias, float $custoTotal): void
    {
        foreach ($materias as $m) {
            ProdutoMateriaPrima::query()->updateOrCreate(
                [
                    'ProdutoID'      => $produto->ProdutoID,
                    'MateriaPrimaID' => $m['MateriaPrimaID'],
                ],
                [
                    'Unidade'       => $m['Unidade'],
                    'Quantidade'    => $m['Quantidade'],
                    'CustoUnitario' => $m['CustoUnitario'],
                    'Custo'         => $m['Custo'],
                ]
            );
        }

        $produto->update(['CustoMedio' => $custoTotal]);
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
                ? $this->getComposicoes($materia)
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
                ? $this->getComposicoes($materia)
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
}
