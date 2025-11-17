<?php

namespace App\Models;

use App\Traits\Models\SalvaEmMaiusculo;
use App\Traits\Models\TemPesquisa;
use Database\Factories\EmpresaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /** @use HasFactory<EmpresaFactory> */
    use HasFactory;
    use TemPesquisa;
    use SalvaEmMaiusculo;

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
        'Email',
        'Ativo'
    ];

    public function getEnderecoCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->Endereco,
            $this->Numero ? ", {$this->Numero}" : null,
            $this->Bairro ? " - {$this->Bairro}" : null,
            ($this->Cidade && $this->Estado) ? "{$this->Cidade}/{$this->Estado}" : null,
        ]);

        if (empty($partes)) {
            return '';
        }

        return implode(' ', $partes);
    }

    public function getCnpjFormatadoAttribute(): ?string
    {
        $v = preg_replace('/\D/', '', $this->CNPJ);
        if (strlen($v) !== 14) {
            return $this->CNPJ;
        }

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $v
        );
    }

    public function getTelefoneFormatadoAttribute(): ?string
    {
        $v = preg_replace('/\D/', '', $this->Telefone);

        if (strlen($v) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $v);
        }

        if (strlen($v) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $v);
        }

        return $this->Telefone;
    }

    public function getCepFormatadoAttribute(): ?string
    {
        $v = preg_replace('/\D/', '', $this->CEP);
        if (strlen($v) !== 8) {
            return $this->CEP;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $v);
    }
}
