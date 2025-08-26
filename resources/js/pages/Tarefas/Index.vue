<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Certifique-se de que Link está importado
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
    horario: string | null;
};

defineProps<{
    tarefas: Array<Tarefa>;
    filtros: {
        estado: string | null;
        prioridade: string | null;
    };
}>();

// **** CÓDIGO RESTAURADO ****
const showingForm = ref(false);
const editingTask = ref<Tarefa | null>(null);

const openCreateForm = () => {
    editingTask.value = null;
    showingForm.value = true;
};

const openEditForm = (tarefa: Tarefa) => {
    editingTask.value = tarefa;
    showingForm.value = true;
};

const closeForm = () => {
    showingForm.value = false;
    editingTask.value = null;
};
// **** FIM DO CÓDIGO RESTAURADO ****
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

                <div class="space-y-2">
                    <!-- **** CÓDIGO RESTAURADO **** -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Estado:</span>
                        <Link :href="route('tarefas.index', { prioridade: filtros.prioridade })"
                            class="px-3 py-1 text-sm rounded-full transition"
                            :class="{ 'bg-blue-500 text-white': !filtros.estado, 'text-gray-700 hover:bg-gray-200': filtros.estado }">
                            Todas
                        </Link>
                        <Link :href="route('tarefas.index', { estado: 'pendente', prioridade: filtros.prioridade })"
                            class="px-3 py-1 text-sm rounded-full transition"
                            :class="{ 'bg-blue-500 text-white': filtros.estado === 'pendente', 'text-gray-700 hover:bg-gray-200': filtros.estado !== 'pendente' }">
                            Pendentes
                        </Link>
                        <Link :href="route('tarefas.index', { estado: 'concluida', prioridade: filtros.prioridade })"
                            class="px-3 py-1 text-sm rounded-full transition"
                            :class="{ 'bg-blue-500 text-white': filtros.estado === 'concluida', 'text-gray-700 hover:bg-gray-200': filtros.estado !== 'concluida' }">
                            Concluídas
                        </Link>
                    </div>
                    <!-- **** FIM DO CÓDIGO RESTAURADO **** -->

                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Prioridade:</span>
                        <Link :href="route('tarefas.index', { estado: filtros.estado })"
                              class="px-3 py-1 text-sm rounded-full transition"
                              :class="{ 'bg-blue-500 text-white': !filtros.prioridade, 'text-gray-700 hover:bg-gray-200': filtros.prioridade }">
                            Todas
                        </Link>
                        <Link :href="route('tarefas.index', { prioridade: 'baixa', estado: filtros.estado })"
                              class="px-3 py-1 text-sm rounded-full transition"
                              :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'baixa', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'baixa' }">
                            Baixa
                        </Link>
                        <Link :href="route('tarefas.index', { prioridade: 'media', estado: filtros.estado })"
                              class="px-3 py-1 text-sm rounded-full transition"
                              :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'media', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'media' }">
                            Média
                        </Link>
                        <Link :href="route('tarefas.index', { prioridade: 'alta', estado: filtros.estado })"
                              class="px-3 py-1 text-sm rounded-full transition"
                              :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'alta', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'alta' }">
                            Alta
                        </Link>
                    </div>
                </div>

                <TarefaForm v-if="showingForm" :tarefa-to-edit="editingTask" @close="closeForm" />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="tarefas.length === 0">
                            Nenhuma tarefa encontrada para este filtro.
                        </div>
                        <div v-else>
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