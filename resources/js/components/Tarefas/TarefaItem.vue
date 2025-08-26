<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';

// TIPO DE DADOS
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

// PROPS
const props = defineProps<{
    tarefa: Tarefa;
    aberta: boolean;
}>();

// EVENTOS
const emit = defineEmits(['edit', 'toggleAberto']);

// FUNÇÕES AUXILIARES
const formatarData = (data: string | null) => {
    if (!data) return 'N/A';
    const dataObj = new Date(data);
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'UTC' };
    return dataObj.toLocaleDateString('pt-PT', options);
};

const formatarHorario = (horario: string | null) => horario ? ` às ${horario.substring(0, 5)}` : '';

// AÇÕES
const toggleEstado = () => {
    router.patch(route('tarefas.toggle', { tarefa: props.tarefa.id }), {}, { preserveScroll: true });
};

const deleteTask = () => {
    if (confirm('Tem a certeza que deseja apagar esta tarefa?')) {
        router.delete(route('tarefas.destroy', { tarefa: props.tarefa.id }));
    }
};

// COMPUTED PROPERTIES (CORRIGIDAS)
const taskCardClasses = computed(() => {
    if (props.tarefa.estado === 'concluida') {
        return 'bg-green-50/60 border-l-green-500';
    }
    switch (props.tarefa.prioridade) {
        case 'alta': return 'bg-red-50/60 border-l-red-500';
        case 'media': return 'bg-yellow-50/60 border-l-yellow-500';
        case 'baixa': return 'bg-blue-50/60 border-l-blue-500';
        default: return 'bg-gray-50 border-l-gray-300';
    }
});

const priorityBadgeClasses = computed(() => {
    switch (props.tarefa.prioridade) {
        case 'alta': return 'bg-red-100 text-red-700';
        case 'media': return 'bg-yellow-100 text-yellow-700';
        case 'baixa': return 'bg-blue-100 text-blue-700';
        default: return 'bg-gray-100 text-gray-700';
    }
});
</script>

<template>
    <div
        class="rounded-lg shadow-sm transition-all duration-300 border-l-4"
        :class="[taskCardClasses, { 'shadow-xl scale-[1.01]': aberta }]"
    >
        <div class="p-4 flex items-center justify-between cursor-pointer" @click="$emit('toggleAberto')">
            <div class="flex items-center flex-grow min-w-0">
                <input
                    type="checkbox"
                    class="checkbox checkbox-primary mr-4 flex-shrink-0"
                    :checked="tarefa.estado === 'concluida'"
                    @change.stop="toggleEstado"
                    @click.stop
                />
                <div class="flex-grow min-w-0">
                    <h3 class="font-bold text-lg text-gray-800 truncate" :class="{ 'line-through': tarefa.estado === 'concluida' }">
                        {{ tarefa.titulo }}
                    </h3>
                    <div class="flex items-center flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-gray-600">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize" :class="priorityBadgeClasses">
                            {{ tarefa.prioridade }}
                        </span>
                        <span v-if="tarefa.data_vencimento">
                            {{ formatarData(tarefa.data_vencimento) }}{{ formatarHorario(tarefa.horario) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-1 pl-4 flex-shrink-0">
                <button @click.stop="$emit('edit')" class="p-2 rounded-full hover:bg-gray-200" title="Editar Tarefa">
                    <Pencil class="w-4 h-4 text-gray-600" />
                </button>
                <button @click.stop="deleteTask" class="p-2 rounded-full hover:bg-red-100" title="Apagar Tarefa">
                    <Trash2 class="w-4 h-4 text-red-600" />
                </button>
            </div>
        </div>

        <div v-if="aberta" class="px-4 pb-4 pt-2 border-t border-gray-200/80">
            <p v-if="tarefa.descricao" class="text-gray-700 whitespace-pre-wrap mb-4">{{ tarefa.descricao }}</p>
            <p v-else class="text-gray-400 italic mb-4">Sem descrição.</p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="font-semibold text-gray-500">Estado</p>
                    <p class="capitalize">{{ tarefa.estado }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-500">Criado em</p>
                    <p>{{ formatarData(tarefa.created_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>