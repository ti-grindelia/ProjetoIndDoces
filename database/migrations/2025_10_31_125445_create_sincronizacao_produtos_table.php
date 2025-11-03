<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('SincronizacaoProdutos', function (Blueprint $table) {
            $table->integer('SincronizacaoProdutoID')->autoIncrement()->primary();
            $table->dateTime('DataSincronizacao')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SincronizacaoProdutos');
    }
};
