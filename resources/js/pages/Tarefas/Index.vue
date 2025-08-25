<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import TarefaForm from '@/components/Tarefas/TarefaForm.vue';
import TarefaItem from '@/components/Tarefas/TarefaItem.vue';

type Tarefa = {
    id: number;
    titulo: string;
    descricao: string | null;
    prioridade: 'baixa' | 'media' | 'alta';
    estado: 'pendente' | 'concluida';
    data_vencimento: string | null;
};

defineProps<{
    tarefas: Array<Tarefa>;
}>();

// A nossa variável de estado para saber se o formulário está aberto
const showingForm = ref(false);
// A nossa nova variável de estado para guardar a tarefa que está a ser editada
const editingTask = ref<Tarefa | null>(null);

// Função para abrir o formulário para criar uma NOVA tarefa
const openCreateForm = () => {
    editingTask.value = null; // Garante que não estamos a editar
    showingForm.value = true;
};

// Nova função para abrir o formulário para EDITAR uma tarefa existente
const openEditForm = (tarefa: Tarefa) => {
    editingTask.value = tarefa; // Guarda a tarefa a ser editada
    showingForm.value = true;
};

// Função para fechar o formulário (limpa tudo)
const closeForm = () => {
    showingForm.value = false;
    editingTask.value = null;
};
</script>

<template>
    <Head title="Minhas Tarefas" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minhas Tarefas</h2>
                    <Button @click="openCreateForm" v-if="!showingForm">Adicionar Tarefa</Button>
                </div>

                <!-- Passamos a tarefa a ser editada para o nosso formulário -->
                <TarefaForm v-if="showingForm" :tarefa-to-edit="editingTask" @close="closeForm" />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="tarefas.length === 0">
                            Ainda não tem nenhuma tarefa. Que tal adicionar uma?
                        </div>
                        <div v-else>
                            <!-- Quando o TarefaItem emite o evento 'edit', chamamos a nossa função openEditForm -->
                            <TarefaItem
                                v-for="tarefa in tarefas"
                                :key="tarefa.id"
                                :tarefa="tarefa"
                                @edit="openEditForm(tarefa)"
                            />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>