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
        Schema::create('Produtos', function (Blueprint $table) {
            $table->integer('ProdutoID')->autoIncrement()->primary();
            $table->string('Descricao', 100);
            $table->text('Descritivo')->nullable();
            $table->string('CodigoAlternativo', 50);
            $table->boolean('Ativo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Produtos');
    }
};
