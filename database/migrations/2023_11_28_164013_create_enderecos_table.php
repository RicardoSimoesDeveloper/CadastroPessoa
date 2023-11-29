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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id('id');

            $table->string('pais', 30);
            $table->char('estado', 2);
            $table->string('cidade', 40);
			$table->string('bairro', 90);
            $table->string('logradouro', 45)->nullable();
			$table->string('numero', 10)->nullable();
			$table->string('complemento', 30)->nullable();
            $table->char('cep', 8)->nullable();

            $table->timestamp('criado_em')->useCurrent();
			$table->timestamp('atualizado_em')->useCurrent();
            $table->softDeletes('excluido_em');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
