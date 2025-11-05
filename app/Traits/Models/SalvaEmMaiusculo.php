<?php

namespace App\Traits\Models;

trait SalvaEmMaiusculo
{
    public static function bootSalvaEmMaiusculo(): void
    {
        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                if (
                    is_string($value)
                    && !in_array($key, ['Senha', 'remember_token', 'Email', 'Usuario'])
                ) {
                    $model->{$key} = mb_strtoupper($value, 'UTF-8');
                }
            }
        });
    }
}
