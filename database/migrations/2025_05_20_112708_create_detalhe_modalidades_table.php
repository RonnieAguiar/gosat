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
        Schema::create('detalhe_modalidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpf_id')->constrained()->onDelete('cascade'); // opcional: associar ao CPF que consultou
            $table->foreignId('modalidade_id')->constrained()->onDelete('cascade');
            $table->integer('qnt_parcela_min')->nullable();
            $table->integer('qnt_parcela_max')->nullable();
            $table->decimal('valor_min', 10, 2)->nullable();
            $table->decimal('valor_max', 10, 2)->nullable();
            $table->decimal('juros_mes', 5, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhe_modalidades');
    }
};
