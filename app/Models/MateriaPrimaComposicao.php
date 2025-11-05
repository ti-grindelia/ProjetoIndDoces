<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MateriaPrimaComposicao extends Model
{
    protected   $table              = 'MateriaPrimaComposicao';
    protected   $primaryKey         = 'MateriaPrimaComposicaoID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    protected $fillable = [
        'MateriaPrimaBaseID',
        'MateriaPrimaFilhaID',
        'Quantidade',
        'CustoUnitario',
        'CustoTotal'
    ];

    public function materiaBase(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'MateriaPrimaBaseID');
    }

    public function materiaFilha(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'MateriaPrimaFilhaID');
    }
}
