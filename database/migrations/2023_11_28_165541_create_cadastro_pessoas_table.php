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
        Schema::create('cadastro_pessoas', function (Blueprint $table) {
            $table->id('id');

            $table->string('nome', 45);
			$table->string('documento_cpf', 11)->nullable();
            $table->date('data_nascimento');
            $table->unsignedBigInteger('endereco');
            $table->string('sexo', 10)->nullable();
            $table->char('telefone', 11)->nullable();
			$table->string('email', 100)->nullable();

            $table->timestamp('criado_em')->useCurrent();
			$table->timestamp('atualizado_em')->useCurrent();
            $table->softDeletes('excluido_em');

            $table->foreign('endereco')->references('id')->on('enderecos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_pessoas');
    }
};
