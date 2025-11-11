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
        Schema::create('MateriaPrimaComposicao', function (Blueprint $table) {
            $table->integer('MateriaPrimaComposicaoID')->primary()->autoIncrement();
            $table->integer('MateriaPrimaBaseID');
            $table->integer('MateriaPrimaFilhaID');
            $table->decimal('Quantidade', 10, 4);
            $table->decimal('CustoUnitario', 10, 2)->nullable();
            $table->decimal('CustoTotal', 10, 2)->nullable();

            $table->foreign('MateriaPrimaBaseID')->references('MateriaPrimaID')->on('MateriasPrimas')->onDelete('cascade');
            $table->foreign('MateriaPrimaFilhaID')->references('MateriaPrimaID')->on('MateriasPrimas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MateriaPrimaComposicao');
    }
};
