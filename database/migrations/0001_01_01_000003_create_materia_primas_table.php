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
            $table->string('CodigoAlternativo', 50)->nullable()->unique();
            $table->string('Descricao', 255);
            $table->string('Unidade', 5);
            $table->decimal('PrecoCompra', 10, 2)->nullable();
            $table->tinyInteger('PermiteComposicao')->default(0);
            $table->decimal('Rendimento', 10, 4)->nullable();
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
