<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            // Adiciona uma nova coluna chamada 'horario' do tipo TIME.
            // 'nullable()' permite que o campo fique vazio.
            // 'after(...)' posiciona a nova coluna logo a seguir à coluna da data.
            $table->time('horario')->nullable()->after('data_vencimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            // Se precisarmos de reverter esta migration, este comando irá apagar a coluna.
            $table->dropColumn('horario');
        });
    }
};
