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
        Schema::create('MateriasPrimas', function (Blueprint $table) {
            $table->integer('MateriaPrimaID')->autoIncrement()->primary();
            $table->string('Nome', 255);
            $table->longText('Descricao');
            $table->string('Fornecedor', 255);
            $table->tinyInteger('Ativo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MateriasPrimas');
    }
};
