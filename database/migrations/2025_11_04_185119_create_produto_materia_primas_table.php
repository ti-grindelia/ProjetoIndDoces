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
        Schema::create('ProdutosMateriasPrimas', function (Blueprint $table) {
            $table->integer('ProdutoMateriaPrimaID')->autoIncrement()->primary();
            $table->integer('ProdutoID');
            $table->integer('MateriaPrimaID');
            $table->string('Unidade', 2);
            $table->decimal('Quantidade', 10, 4);
            $table->decimal('CustoUnitario', 10, 2);
            $table->decimal('Custo', 10, 2);
            $table->foreign('ProdutoID')->references('ProdutoID')->on('Produtos');
            $table->foreign('MateriaPrimaID')->references('MateriaPrimaID')->on('MateriasPrimas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProdutosMateriasPrimas');
    }
};
