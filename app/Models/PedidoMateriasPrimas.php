<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoMateriasPrimas extends Model
{
    protected $table = 'PedidosMateriasPrimas';
    protected $primaryKey = 'PedidoMateriaPrimaID';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'PedidoID',
        'MateriaPrimaID',
        'Quantidade',
        'CustoTotal'
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'PedidoID');
    }

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'MateriaPrimaID');
    }
}
