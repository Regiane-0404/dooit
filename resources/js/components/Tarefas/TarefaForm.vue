<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { watch } from 'vue';

type Tarefa = {
    id: number;
    titulo: string;
    descricao: string | null;
    prioridade: 'baixa' | 'media' | 'alta';
    data_vencimento: string | null;
    horario: string | null; // 1. Adicionamos 'horario' ao nosso tipo Tarefa
};

const props = defineProps<{
    tarefaToEdit?: Tarefa | null;
}>();

const emit = defineEmits(['close']);

const form = useForm({
    titulo: props.tarefaToEdit?.titulo ?? '',
    descricao: props.tarefaToEdit?.descricao ?? '',
    prioridade: props.tarefaToEdit?.prioridade ?? 'media',
    data_vencimento: props.tarefaToEdit?.data_vencimento ?? '',
    horario: props.tarefaToEdit?.horario ?? '', // 2. Adicionamos 'horario' ao formulário
});

watch(() => props.tarefaToEdit, (newVal) => {
    form.defaults({
        titulo: newVal?.titulo ?? '',
        descricao: newVal?.descricao ?? '',
        prioridade: newVal?.prioridade ?? 'media',
        data_vencimento: newVal?.data_vencimento ?? '',
        horario: newVal?.horario ?? '', // 3. Atualizamos 'horario' quando editamos
    }).reset();
});

const submit = () => {
    // ... (função submit não precisa de alterações)
    if (props.tarefaToEdit) {
        form.put(route('tarefas.update', { tarefa: props.tarefaToEdit.id }), {
            onSuccess: () => closeForm(),
        });
    } else {
        form.post(route('tarefas.store'), {
            onSuccess: () => closeForm(),
        });
    }
};

const closeForm = () => {
    emit('close');
};
</script>

<template>
    <div class="p-6 bg-gray-50 rounded-lg shadow-md">
        <h3 class="font-bold text-lg">{{ tarefaToEdit ? 'Editar Tarefa' : 'Adicionar Nova Tarefa' }}</h3>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <!-- Campos existentes -->
            <div>
                <Label for="titulo">Título</Label>
                <Input id="titulo" v-model="form.titulo" type="text" class="mt-1 block w-full" required autofocus />
                <InputError class="mt-2" :message="form.errors.titulo" />
            </div>

            <div>
                <Label for="descricao">Descrição (Opcional)</Label>
                <Input id="descricao" v-model="form.descricao" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.descricao" />
            </div>

            <div>
                <Label for="prioridade">Prioridade</Label>
                <select id="prioridade" v-model="form.prioridade" class="select select-bordered w-full mt-1 font-normal">
                    <option value="baixa">Baixa</option>
                    <option value="media">Média</option>
                    <option value="alta">Alta</option>
                </select>
                <InputError class="mt-2" :message="form.errors.prioridade" />
            </div>

            <!-- 4. Adicionamos os campos de data e hora lado a lado -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label for="data_vencimento">Data de Vencimento</Label>
                    <Input id="data_vencimento" v-model="form.data_vencimento" type="date" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.data_vencimento" />
                </div>
                <div>
                    <Label for="horario">Horário</Label>
                    <Input id="horario" v-model="form.horario" type="time" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.horario" />
                </div>
            </div>


            <div class="flex items-center justify-end gap-4">
                <Button type="button" variant="ghost" @click="closeForm">Cancelar</Button>
                <Button :disabled="form.processing">Guardar</Button>
            </div>
        </form>
    </div>
</template>