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
        Schema::create('Pedidos', function (Blueprint $table) {
            $table->integer('PedidoID')->autoIncrement()->primary();
            $table->integer('EmpresaID');
            $table->integer('UsuarioID');
            $table->dateTime('DataInclusao');
            $table->string('Status', 20)->default('Aberto');
            $table->decimal('CustoTotal', 10, 2);
            $table->dateTime('AlteradoEm')->nullable();
            $table->integer('AlteradoPor')->nullable();
            $table->dateTime('CanceladoEm')->nullable();
            $table->integer('CanceladoPor')->nullable();
            $table->foreign('EmpresaID')->references('EmpresaID')->on('Empresas');
            $table->foreign('UsuarioID')->references('UsuarioID')->on('Usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Pedidos');
    }
};
