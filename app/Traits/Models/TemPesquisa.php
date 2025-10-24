<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TemPesquisa
{
    public function scopePesquisa(Builder $query, ?string $pesquisa = null, ?array $colunas = []): Builder
    {
        return $query
            ->when($pesquisa, function (Builder $q) use ($pesquisa, $colunas) {
                $q->where(function ($subQuery) use ($pesquisa, $colunas) {
                    foreach ($colunas as $coluna) {
                        $subQuery->orWhere(
                            DB::raw('LOWER(' . $coluna . ')'),
                            'like',
                            '%' . strtolower($pesquisa) . '%'
                        );
                    }
                });
            });
    }
}
