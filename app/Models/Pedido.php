<?php

namespace App\Models;

use App\Traits\Models\TemPesquisa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use TemPesquisa;

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

    protected $casts = [
        'DataInclusao' => 'datetime',
        'CustoTotal'   => 'decimal:2',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'EmpresaID');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'UsuarioID');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItens::class, 'PedidoID');
    }

    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(PedidoMateriasPrimas::class, 'PedidoID');
    }

    public function getDataFormatadaAttribute(): string
    {
        return $this->DataInclusao
            ? $this->DataInclusao->format('d/m/Y H:i')
            : '-';
    }

    public function getCustoFormatadoAttribute(): string
    {
        return $this->CustoTotal
            ? 'R$ ' . number_format($this->CustoTotal, 2, ',', '.')
            : 'R$ 0,00';
    }
}
