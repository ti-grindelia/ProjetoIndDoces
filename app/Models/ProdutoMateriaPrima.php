<?php

namespace App\Models;

use App\Traits\Models\SalvaEmMaiusculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdutoMateriaPrima extends Model
{
    use SalvaEmMaiusculo;

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

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'ProdutoMateriaPrimaID', 'ProdutoID');
    }

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'MateriaPrimaID', 'MateriaPrimaID');
    }
}
