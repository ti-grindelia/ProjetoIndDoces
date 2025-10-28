<?php

namespace App\Models;

use App\Traits\Models\TemPesquisa;
use Database\Factories\EmpresaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /** @use HasFactory<EmpresaFactory> */
    use HasFactory;
    use TemPesquisa;

    protected   $table              = 'Empresas';
    protected   $primaryKey         = 'EmpresaID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    protected $fillable = [
        'CNPJ',
        'RazaoSocial',
        'CEP',
        'Endereco',
        'Numero',
        'Complemento',
        'Bairro',
        'Cidade',
        'Estado',
        'Telefone',
        'Email'
    ];
}
