<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoMateriaPrima extends Model
{
    protected $table = "ProdutosMateriasPrimas";
    protected $primaryKey = "ProdutoMateriaPrimaID";
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable     = [
        'ProdutoID',
        'MateriaPrimaID',
        'Unidade',
        'Quantidade',
        'CustoUnitario',
        'Custo',
    ];
}
