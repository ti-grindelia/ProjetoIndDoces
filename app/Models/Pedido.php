<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    protected $table = 'Pedidos';
    protected $primaryKey = 'PedidoID';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'EmpresaID',
        'UsuarioID',
        'DataInclusao',
        'Status',
        'CustoTotal',
        'AlteradoEm',
        'AlteradoPor',
        'CanceladoEm',
        'CanceladoPor'
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'EmpresaID');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'UsuarioID');
    }
}
