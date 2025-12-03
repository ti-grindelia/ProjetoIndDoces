<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItens extends Model
{
    protected $table = 'PedidosItens';
    protected $primaryKey = 'PedidoItemID';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'PedidoID',
        'ProdutoID',
        'Quantidade',
        'CustoTotal'
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'PedidoID');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'ProdutoID');
    }
}
