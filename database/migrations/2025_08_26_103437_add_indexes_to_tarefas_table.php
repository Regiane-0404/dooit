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
        Schema::table('tarefas', function (Blueprint $table) {
            // Adiciona índices às colunas que usamos frequentemente para filtrar e ordenar.
            $table->index('estado');
            $table->index('prioridade');
            $table->index('data_vencimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            // Se precisarmos de reverter, este comando remove os índices.
            $table->dropIndex(['estado']);
            $table->dropIndex(['prioridade']);
            $table->dropIndex(['data_vencimento']);
        });
    }
};