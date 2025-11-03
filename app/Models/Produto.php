<?php

namespace App\Models;

use App\Traits\Models\TemPesquisa;
use Database\Factories\ProdutoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    /** @use HasFactory<ProdutoFactory> */
    use HasFactory;
    use TemPesquisa;

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
        'Fracionado',
        'UltimaSincronizacao',
        'Ativo'
    ];

    protected $casts = [
        'Ativo' => 'boolean'
    ];

    public function getCategoriaIdAttribute(): string
    {
        return "$this->ProdutoCategoriaID - $this->Categoria";
    }
}
