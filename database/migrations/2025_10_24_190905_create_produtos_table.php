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
            $table->string('CodigoAlternativo', 30)->nullable();
            $table->string('Descricao', 100);
            $table->text('Descritivo')->nullable();
            $table->integer('ProdutoCategoriaID');
            $table->string('Categoria', 100);
            $table->decimal('Preco', 10, 2)->nullable();
            $table->decimal('CustoMedio', 10, 2)->nullable();
            $table->integer('EmpresaID')->nullable();
            $table->foreignIdFor('EmpresaID')->references('EmpresaID')->on('Empresas');
            $table->boolean('Fracionado')->default(false);
            $table->dateTime('UltimaSincronizacao')->nullable();
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
