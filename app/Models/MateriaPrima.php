<?php

namespace App\Models;

use App\Traits\Models\SalvaEmMaiusculo;
use App\Traits\Models\TemPesquisa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MateriaPrima extends Model
{
    use HasFactory;
    use TemPesquisa;
    use SalvaEmMaiusculo;

    protected   $table              = 'MateriasPrimas';
    protected   $primaryKey         = 'MateriaPrimaID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    protected $fillable = [
        'CodigoAlternativo',
        'Descricao',
        'Unidade',
        'PrecoCompra',
        'PermiteComposicao',
        'Rendimento',
        'Ativo'
    ];

    protected $casts = [
        'PermiteComposicao' => 'boolean',
        'Ativo' => 'boolean'
    ];

    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(
            MateriaPrima::class,
            'MateriaPrimaComposicao',
            'MateriaPrimaBaseID',
            'MateriaPrimaFilhaID'
        )->withPivot(['Quantidade', 'CustoUnitario', 'CustoTotal']);
    }

    public function utilizadaEm(): BelongsToMany
    {
        return $this->belongsToMany(
            MateriaPrima::class,
            'MateriaPrimaComposicao',
            'MateriaPrimaFilhaID',
            'MateriaPrimaBaseID'
        )->withPivot(['Quantidade', 'CustoUnitario', 'CustoTotal']);
    }

    public function materiasProdutos(): BelongsToMany
    {
        return $this->belongsToMany(ProdutoMateriaPrima::class, 'ProdutoMateriaPrima', 'MateriaPrimaID', 'MateriaPrimaID');
    }
}
