<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            // Adicionamos a nova coluna.
            // 'after('id')' posiciona-a no início para organização.
            // 'nullable()' é TEMPORÁRIO para que as tarefas existentes não dêem erro.
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });

        // ATUALIZAÇÃO: Atribuímos todas as tarefas existentes ao primeiro utilizador.
        // Num cenário real, aqui teríamos uma lógica mais complexa.
        $primeiroUtilizador = \App\Models\User::first();
        if ($primeiroUtilizador) {
            \App\Models\Tarefa::whereNull('user_id')->update(['user_id' => $primeiroUtilizador->id]);
        }

        // TORNAR A COLUNA OBRIGATÓRIA: Agora que todas as linhas têm um valor,
        // podemos tornar a coluna não-nula, como devia ser.
        Schema::table('tarefas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            // O método 'down' reverte a operação, apagando a coluna.
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};