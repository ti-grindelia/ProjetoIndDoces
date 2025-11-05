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
        Schema::create('Empresas', function (Blueprint $table) {
            $table->integer('EmpresaID')->autoIncrement()->primary();
            $table->string('CNPJ', 18)->unique();
            $table->string('RazaoSocial', 255);
            $table->string('CEP', 9);
            $table->string('Endereco', 255);
            $table->string('Numero', 10);
            $table->string('Complemento', 255)->nullable();
            $table->string('Bairro', 255);
            $table->string('Cidade', 255);
            $table->string('Estado', 2);
            $table->string('Telefone', 15)->nullable();
            $table->string('Email', 255)->nullable();
            $table->tinyInteger('Ativo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Empresas');
    }
};
