<?php

namespace App\Models;

use App\Traits\Models\TemPesquisa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrima extends Model
{
    use HasFactory;
    use TemPesquisa;

    protected   $table              = 'MateriasPrimas';
    protected   $primaryKey         = 'MateriaPrimaID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    protected $fillable = [
        'CodigoAlternativo',
        'Nome',
        'Unidade',
        'Valor',
        'CustoMedio',
        'Ativo'
    ];
}
