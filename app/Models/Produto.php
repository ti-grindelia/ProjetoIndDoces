<?php

namespace App\Models;

use App\Traits\Models\SalvaEmMaiusculo;
use App\Traits\Models\TemPesquisa;
use Database\Factories\ProdutoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produto extends Model
{
    /** @use HasFactory<ProdutoFactory> */
    use HasFactory;
    use TemPesquisa;
    use SalvaEmMaiusculo;

    protected $table = "Produtos";
    protected $primaryKey = "ProdutoID";
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable     = [
        'CodigoAlternativo',
        'Descricao',
        'Descritivo',
        'ProdutoCategoriaID',
        'Categoria',
        'Preco',
        'CustoMedio',
        'EmpresaID',
        'Fracionado',
        'UltimaSincronizacao',
        'PesoUnidade',
        'Ativo'
    ];

    protected $casts = [
        'Ativo' => 'boolean'
    ];

    public function getCategoriaIdAttribute(): string
    {
        return "$this->ProdutoCategoriaID - $this->Categoria";
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'EmpresaID');
    }

    public function materiasPrimas(): BelongsToMany
    {
        return $this->belongsToMany(MateriaPrima::class, 'ProdutoMateriaPrima', 'ProdutoID', 'MateriaPrimaID');
    }
}
