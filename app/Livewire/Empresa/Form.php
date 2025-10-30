<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use Illuminate\Support\Facades\Http;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Empresa $empresa = null;

    public string $cnpj = '';

    public string $razaoSocial = '';

    public string $cep = '';

    public string $endereco = '';

    public string $numero = '';

    public ?string $complemento = null;

    public string $bairro = '';

    public string $cidade = '';

    public string $estado = '';

    public ?string $telefone = null;

    public ?string $email = null;

    public bool $ativo = true;

    public bool $enderecoBloqueado = false;

    public function rules(): array
    {
        return [
            'cnpj'        => ['required', 'min:14', 'max:19'],
            'razaoSocial' => ['required', 'min:3', 'max:255'],
            'cep'         => ['required', 'min:9', 'max:9'],
            'endereco'    => ['required', 'min:3', 'max:255'],
            'numero'      => ['required', 'min:1', 'max:10'],
            'complemento' => ['nullable', 'min:3', 'max:255'],
            'bairro'      => ['required', 'min:3', 'max:255'],
            'cidade'      => ['required', 'min:3', 'max:255'],
            'estado'      => ['required', 'min:2', 'max:2'],
            'telefone'    => ['nullable', 'min:10', 'max:16'],
            'email'       => ['nullable', 'email', 'max:255'],
            'ativo'       => ['boolean'],
        ];
    }

    public function setEmpresa(Empresa $empresa): void
    {
        $this->empresa = $empresa;

        $this->cnpj        = $this->formatarCnpj($empresa->CNPJ);
        $this->razaoSocial = $empresa->RazaoSocial;
        $this->cep         = $this->formatarCep($empresa->CEP);
        $this->endereco    = $empresa->Endereco;
        $this->numero      = $empresa->Numero;
        $this->complemento = $empresa->Complemento;
        $this->bairro      = $empresa->Bairro;
        $this->cidade      = $empresa->Cidade;
        $this->estado      = $empresa->Estado;
        $this->telefone    = $this->formatarTelefone($empresa->Telefone);
        $this->email       = $empresa->Email;
        $this->ativo       = $empresa->Ativo;
    }

    public function criar(): void
    {
        $this->validate();

        Empresa::create([
            'CNPJ'        => preg_replace('/[^0-9]/', '', $this->cnpj),
            'RazaoSocial' => $this->razaoSocial,
            'CEP'         => preg_replace('/[^0-9]/', '', $this->cep),
            'Endereco'    => $this->endereco,
            'Numero'      => $this->numero,
            'Complemento' => $this->complemento,
            'Bairro'      => $this->bairro,
            'Cidade'      => $this->cidade,
            'Estado'      => $this->estado,
            'Telefone'    => preg_replace('/[^0-9]/', '', $this->telefone),
            'Email'       => $this->email,
            'Ativo'       => $this->ativo,
        ]);

        $this->reset();
    }

    public function atualizar(): void
    {
        $this->validate();

        $this->empresa->CNPJ        = preg_replace('/[^0-9]/', '', $this->cnpj);
        $this->empresa->RazaoSocial = $this->razaoSocial;
        $this->empresa->CEP         = preg_replace('/[^0-9]/', '', $this->cep);
        $this->empresa->Endereco    = $this->endereco;
        $this->empresa->Numero      = $this->numero;
        $this->empresa->Complemento = $this->complemento;
        $this->empresa->Bairro      = $this->bairro;
        $this->empresa->Cidade      = $this->cidade;
        $this->empresa->Estado      = $this->estado;
        $this->empresa->Telefone    = preg_replace('/[^0-9]/', '', $this->telefone);
        $this->empresa->Email       = $this->email;
        $this->empresa->Ativo       = $this->ativo;

        $this->empresa->update();
    }

    public function buscarEndereco(): void
    {
        $cep = preg_replace('/[^0-9]/', '', $this->cep);

        if (strlen($cep) !== 8) {
            $this->addError('cep', 'CEP inválido');
            return;
        }

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();

            if (isset($response['erro'])) {
                $this->addError('cep', 'CEP não encontrado');
                return;
            }

            $this->endereco = $response['logradouro'] ?? '';
            $this->numero = '';
            $this->complemento = $response['complemento'] ?? '';
            $this->bairro = $response['bairro'] ?? '';
            $this->cidade = $response['localidade'] ?? '';
            $this->estado = $response['uf'] ?? '';

            $this->enderecoBloqueado = true;
        } catch (\Exception $e) {
            $this->addError('cep', 'Erro ao buscar CEP');
        }
    }

    private function formatarCnpj(string $cnpj): string
    {
        $v = preg_replace('/\D/', '', $cnpj);
        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $v
        );
    }

    private function formatarTelefone(string $telefone): string
    {
        $v = preg_replace('/\D/', '', $telefone);
        if (strlen($v) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $v);
        } elseif (strlen($v) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $v);
        }
        return $telefone;
    }

    private function formatarCep(string $cep): string
    {
        $v = preg_replace('/\D/', '', $cep);
        if (strlen($v) > 5) {
            return preg_replace('/(\d{5})(\d{0,3})/', '$1-$2', $v);
        }
        return $v;
    }
}
