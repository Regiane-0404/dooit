<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    tarefa: {
        id: number;
        titulo: string;
        descricao: string | null;
        prioridade: 'baixa' | 'media' | 'alta';
        estado: 'pendente' | 'concluida';
        data_vencimento: string | null;
        horario: string | null; // Adicionamos 'horario' aqui
    };
}>();

const emit = defineEmits(['edit']);

// Nova função para formatar a data para o padrão dd/mm/aaaa
const formatarData = (data: string | null) => {
    if (!data) return null;
    const [ano, mes, dia] = data.split('-');
    return `${dia}/${mes}/${ano}`;
};

const toggleTask = () => {
    // ... (código existente, sem alterações)
    router.patch(route('tarefas.toggle', { tarefa: props.tarefa.id }), {}, {
        preserveScroll: true,
    });
};

const deleteTask = () => {
    // ... (código existente, sem alterações)
    if (confirm('Tem a certeza que deseja apagar esta tarefa?')) {
        router.delete(route('tarefas.destroy', { tarefa: props.tarefa.id }));
    }
};

const priorityColorClass = computed(() => {
    // ... (código existente, sem alterações)
    switch (props.tarefa.prioridade) {
        case 'alta':
            return 'border-l-red-500';
        case 'media':
            return 'border-l-yellow-500';
        case 'baixa':
            return 'border-l-blue-500';
        default:
            return 'border-l-gray-300';
    }
});
</script>

<template>
    <div class="border-b last:border-b-0 py-4 pl-4 flex items-center justify-between border-l-4" :class="[priorityColorClass, { 'opacity-50': tarefa.estado === 'concluida' }]">
        <div class="flex items-center">
            <input
                type="checkbox"
                class="checkbox checkbox-primary mr-4"
                :checked="tarefa.estado === 'concluida'"
                @change="toggleTask"
            />
            <div>
                <h3 class="font-semibold" :class="{ 'line-through': tarefa.estado === 'concluida' }">
                    {{ tarefa.titulo }}
                </h3>
                <p v-if="tarefa.descricao" class="text-sm text-gray-600">
                    {{ tarefa.descricao }}
                </p>

                <!-- **** A MUDANÇA ESTÁ AQUI **** -->
                <!-- Novo container para mostrar a data e a hora -->
                <div v-if="tarefa.data_vencimento" class="text-xs text-gray-500 mt-1">
                    <span>Vencimento: {{ formatarData(tarefa.data_vencimento) }}</span>
                    <span v-if="tarefa.horario"> às {{ tarefa.horario.substring(0, 5) }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <button @click="$emit('edit')" class="btn btn-ghost btn-sm">Editar</button>
            <button @click="deleteTask" class="btn btn-ghost btn-sm text-red-500">Apagar</button>
        </div>
    </div>
</template>
