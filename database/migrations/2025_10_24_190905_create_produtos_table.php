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
            $table->foreign('EmpresaID')->references('EmpresaID')->on('Empresas');
            $table->boolean('Fracionado')->default(false);
            $table->dateTime('UltimaSincronizacao')->nullable();
            $table->decimal('PesoUnidade', 10, 4)->nullable();
            $table->decimal('RendimentoProducao', 10, 4)->nullable();
            $table->boolean('Ativo')->default(true);
            $table->decimal('CustoMateriaPrima', 10, 2)->nullable();
            $table->decimal('CustoIndustrializacao', 10, 2)->nullable();
            $table->decimal('CustoTotal', 10, 2)->nullable();
            $table->decimal('MVAPercentual', 10, 2)->nullable();
            $table->decimal('ValorMVA', 10, 2)->nullable();
            $table->decimal('ICMSPercentual', 10, 2)->nullable();
            $table->decimal('ValorICMS', 10, 2)->nullable();
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
