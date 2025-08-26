<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import TarefaForm from '@/components/Tarefas/TarefaForm.vue';
import TarefaItem from '@/components/Tarefas/TarefaItem.vue';

// TIPO DE DADOS (CORRIGIDO)
type Tarefa = {
    id: number;
    titulo: string;
    descricao: string | null;
    prioridade: 'baixa' | 'media' | 'alta';
    estado: 'pendente' | 'concluida';
    data_vencimento: string | null;
    horario: string | null;
    created_at: string;
};

// PROPS (CORRIGIDO)
const props = defineProps<{
    tarefas: Array<Tarefa>;
    filtros: {
        estado: string | null;
        prioridade: string | null;
        data_vencimento: string | null;
    };
}>();

// ESTADO DO FORMULÁRIO
const showingForm = ref(false);
const editingTask = ref<Tarefa | null>(null);

// ESTADO DO ACORDEÃO
const tarefaAbertaId = ref<number | null>(null);

// ESTADO DO FILTRO DE DATA
const dateFilter = ref(props.filtros.data_vencimento || '');

// FUNÇÕES DO FORMULÁRIO (CORRIGIDAS)
const openCreateForm = () => {
    editingTask.value = null;
    showingForm.value = true;
    tarefaAbertaId.value = null; // Fecha qualquer card aberto
};

const openEditForm = (tarefa: Tarefa) => {
    editingTask.value = tarefa;
    showingForm.value = true;
    tarefaAbertaId.value = null; // Fecha qualquer card aberto
};

const closeForm = () => {
    showingForm.value = false;
    editingTask.value = null;
};

// FUNÇÃO DO ACORDEÃO (CORRIGIDA)
const toggleAberto = (id: number) => {
    if (showingForm.value) return; // Não permite abrir/fechar se o form estiver aberto
    tarefaAbertaId.value = tarefaAbertaId.value === id ? null : id;
};

// WATCHER DO FILTRO DE DATA (CORRIGIDO)
watch(dateFilter, (newValue) => {
    router.get(route('tarefas.index'), {
        estado: props.filtros.estado,
        prioridade: props.filtros.prioridade,
        data_vencimento: newValue,
    }, {
        preserveState: true,
        replace: true,
    });
});
</script>

<template>
    <Head title="Minhas Tarefas" />
    <AppLayout>
        <div class="min-h-full bg-slate-50">
            <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">Minhas Tarefas</h1>
                    <Button @click="openCreateForm" v-if="!showingForm">Adicionar Tarefa</Button>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-sm">
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-600">Estado:</span>
                            <Link :href="route('tarefas.index', { prioridade: filtros.prioridade, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': !filtros.estado, 'text-gray-700 hover:bg-gray-200': filtros.estado }">Todas</Link>
                            <Link :href="route('tarefas.index', { estado: 'pendente', prioridade: filtros.prioridade, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': filtros.estado === 'pendente', 'text-gray-700 hover:bg-gray-200': filtros.estado !== 'pendente' }">Pendentes</Link>
                            <Link :href="route('tarefas.index', { estado: 'concluida', prioridade: filtros.prioridade, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': filtros.estado === 'concluida', 'text-gray-700 hover:bg-gray-200': filtros.estado !== 'concluida' }">Concluídas</Link>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-600">Prioridade:</span>
                            <Link :href="route('tarefas.index', { estado: filtros.estado, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': !filtros.prioridade, 'text-gray-700 hover:bg-gray-200': filtros.prioridade }">Todas</Link>
                            <Link :href="route('tarefas.index', { prioridade: 'baixa', estado: filtros.estado, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'baixa', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'baixa' }">Baixa</Link>
                            <Link :href="route('tarefas.index', { prioridade: 'media', estado: filtros.estado, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'media', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'media' }">Média</Link>
                            <Link :href="route('tarefas.index', { prioridade: 'alta', estado: filtros.estado, data_vencimento: filtros.data_vencimento })" class="px-3 py-1 text-sm rounded-full transition" :class="{ 'bg-blue-500 text-white': filtros.prioridade === 'alta', 'text-gray-700 hover:bg-gray-200': filtros.prioridade !== 'alta' }">Alta</Link>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-600">Vencimento:</span>
                            <Input type="date" v-model="dateFilter" class="max-w-xs text-sm" />
                        </div>
                    </div>
                </div>

                <TarefaForm v-if="showingForm" :tarefa-to-edit="editingTask" @close="closeForm" />

                <div class="space-y-4">
                    <div v-if="tarefas.length === 0" class="text-center py-16 bg-white rounded-lg shadow-sm">
                        <p class="text-gray-500">Nenhuma tarefa encontrada para os filtros selecionados.</p>
                    </div>
                    <div v-else>
                        <TarefaItem
                            v-for="tarefa in tarefas"
                            :key="tarefa.id"
                            :tarefa="tarefa"
                            :aberta="tarefaAbertaId === tarefa.id"
                            @edit="openEditForm(tarefa)"
                            @toggleAberto="toggleAberto(tarefa.id)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>