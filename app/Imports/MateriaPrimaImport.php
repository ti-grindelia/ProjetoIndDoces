<?php

namespace App\Imports;

use App\Models\MateriaPrima;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MateriaPrimaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return Model|null
    */
    public function model(array $row): Model|MateriaPrima|null
    {
        $row = collect($row)
            ->keyBy(fn($value, $key) => str(
                strtolower(trim(str_replace(
                    [' ', 'ã', 'õ', 'ç', 'é', 'ê', 'ó', 'í', 'ú', 'á'],
                    ['_', 'a', 'o', 'c', 'e', 'e', 'o', 'i', 'u', 'a'],
                    $key
                )))
            ))
            ->toArray();

        return new MateriaPrima([
            'CodigoAlternativo' => $row['codigo'] ?? null,
            'Descricao' => $row['descricao_do_produto'] ?? null,
            'Unidade' => $row['unidade'] ?? null,
            'PrecoCompra' => isset($row['preco_compra'])
                ? str_replace(',', '.', $row['preco_compra'])
                : 0,
            'Ativo' => true,
        ]);
    }
}
