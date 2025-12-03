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
        Schema::create('PedidosMateriasPrimas', function (Blueprint $table) {
            $table->integer('PedidoMateriaPrimaID')->autoIncrement()->primary();
            $table->integer('PedidoID');
            $table->integer('MateriaPrimaID');
            $table->decimal('Quantidade', 10, 4);
            $table->decimal('CustoTotal', 10, 2);
            $table->foreign('PedidoID')->references('PedidoID')->on('Pedidos');
            $table->foreign('MateriaPrimaID')->references('MateriaPrimaID')->on('MateriasPrimas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PedidosMateriasPrimas');
    }
};
