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
        Schema::create('PedidosItens', function (Blueprint $table) {
            $table->integer('PedidoItemID')->autoIncrement()->primary();
            $table->integer('PedidoID');
            $table->integer('ProdutoID');
            $table->decimal('Quantidade', 10, 4);
            $table->decimal('CustoTotal', 10, 2);
            $table->foreign('PedidoID')->references('PedidoID')->on('Pedidos');
            $table->foreign('ProdutoID')->references('ProdutoID')->on('Produtos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PedidoItens');
    }
};
