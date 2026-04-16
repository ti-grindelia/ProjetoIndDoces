<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        if (!self::validar($value)) {
            $fail('O campo :attribute deve conter um CNPJ válido.');
        }
    }

    public static function validar(string $cnpj): bool
    {
        $cnpj = self::normalizar($cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (!preg_match('/^[A-Z0-9]{14}$/', $cnpj)) {
            return false;
        }

        // evitar repetidos, tipo 111111111..., AAAAAAAAA...
        if (preg_match('/^([A-Z0-9])\1{13}$/', $cnpj)) {
            return false;
        }

        $base = substr($cnpj, 0, 12);
        $dvInformado = substr($cnpj, 12, 2);

        $dv1 = self::calcularDigito($base, [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
        $dv2 = self::calcularDigito($base . $dv1, [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);

        return $dvInformado === $dv1 . $dv2;
    }

    public static function normalizar(?string $cnpj): string
    {
        return preg_replace('/[^A-Za-z0-9]/', '', strtoupper($cnpj) ?? '');
    }

    private static function calcularDigito(string $base, array $pesos): int
    {
        $soma = 0;

        for ($i = 0; $i < strlen($base); $i++) {
            $valor = ord($base[$i]) - 48;
            $soma += $valor * $pesos[$i];
        }

        $resto = $soma % 11;

        return ($resto < 2) ? '0' : (string)(11 - $resto);
    }
}
