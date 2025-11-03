<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SincronizacaoProduto extends Model
{
    protected   $table              = 'SincronizacaoProdutos';
    protected   $primaryKey         = 'SincronizacaoProdutoID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    protected $fillable = [
        'DataSincronizacao'
    ];
}
