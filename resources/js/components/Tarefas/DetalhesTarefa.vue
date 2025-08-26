<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button'; // Importamos o nosso componente de botão

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

const props = defineProps<{
    tarefa: Tarefa | null;
    show: boolean;
}>();

const emit = defineEmits(['close']);

const formatarData = (dataString: string | null) => {
    if (!dataString) return 'N/A';
    const data = new Date(dataString);
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'UTC' };
    return data.toLocaleDateString('pt-PT', options);
};

const formatarHorario = (horarioString: string | null) => {
    if (!horarioString) return '';
    return ` às ${horarioString.substring(0, 5)}`;
};

const priorityColorClass = computed(() => {
    if (!props.tarefa) return 'border-t-gray-300';
    switch (props.tarefa.prioridade) {
        case 'alta': return 'border-t-red-500';
        case 'media': return 'border-t-yellow-500';
        case 'baixa': return 'border-t-blue-500';
        default: return 'border-t-gray-300';
    }
});
</script>

<template>
    <!-- CORREÇÃO: A sintaxe :class estava errada. Agora está correta. -->
    <div class="modal modal-bottom sm:modal-middle" :class="{ 'modal-open': show }">
        <div class="modal-box border-t-4" :class="priorityColorClass" v-if="tarefa">
            <!-- Botão de fechar (X) no canto -->
            <button @click="$emit('close')" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

            <h3 class="font-bold text-2xl border-b pb-2 mb-4">{{ tarefa.titulo }}</h3>

            <div class="space-y-4">
                <p v-if="tarefa.descricao" class="bg-slate-50 p-3 rounded-md text-gray-700 whitespace-pre-wrap">{{ tarefa.descricao }}</p>
                <div v-else class="bg-slate-50 p-3 rounded-md text-gray-400 italic">Sem descrição.</div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-semibold text-gray-500">Estado</p>
                        <p class="capitalize badge" :class="{ 'badge-success': tarefa.estado === 'concluida', 'badge-warning': tarefa.estado === 'pendente' }">{{ tarefa.estado }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500">Prioridade</p>
                        <p class="capitalize">{{ tarefa.prioridade }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500">Vencimento</p>
                        <p>{{ formatarData(tarefa.data_vencimento) }}{{ formatarHorario(tarefa.horario) }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500">Criado em</p>
                        <p>{{ formatarData(tarefa.created_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Botão de Ação "Fechar" com estilo -->
            <div class="modal-action mt-6">
                <Button @click="$emit('close')" variant="outline">Fechar</Button>
            </div>
        </div>
        <!-- Backdrop para fechar ao clicar fora -->
        <div class="modal-backdrop" @click="$emit('close')"></div>
    </div>
</template>