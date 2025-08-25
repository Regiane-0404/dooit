<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { watch } from 'vue'; // Importamos o 'watch' do Vue

// Definimos um tipo para a nossa Tarefa para manter o código limpo
type Tarefa = {
    id: number;
    titulo: string;
    descricao: string | null;
    prioridade: 'baixa' | 'media' | 'alta';
    data_vencimento: string | null;
};

// O componente agora pode receber uma tarefa para editar (tarefaToEdit)
const props = defineProps<{
    tarefaToEdit?: Tarefa | null; // O '?' torna a prop opcional
}>();

const emit = defineEmits(['close']);

// Se estivermos a editar (tarefaToEdit não é nulo), preenchemos o formulário com os seus dados.
// Caso contrário, começamos com um formulário vazio.
const form = useForm({
    titulo: props.tarefaToEdit?.titulo ?? '',
    descricao: props.tarefaToEdit?.descricao ?? '',
    prioridade: props.tarefaToEdit?.prioridade ?? 'media',
    data_vencimento: props.tarefaToEdit?.data_vencimento ?? '',
});

// Usamos o 'watch' para observar a prop 'tarefaToEdit'.
// Se ela mudar (ou seja, se o utilizador clicar em "Editar" noutra tarefa),
// nós atualizamos os campos do formulário com os novos dados.
watch(() => props.tarefaToEdit, (newVal) => {
    form.defaults({
        titulo: newVal?.titulo ?? '',
        descricao: newVal?.descricao ?? '',
        prioridade: newVal?.prioridade ?? 'media',
        data_vencimento: newVal?.data_vencimento ?? '',
    }).reset();
});

const submit = () => {
    if (props.tarefaToEdit) {
        // Se estamos a editar, fazemos um pedido PUT para a rota 'tarefas.update'.
        form.put(route('tarefas.update', { tarefa: props.tarefaToEdit.id }), {
            onSuccess: () => closeForm(),
        });
    } else {
        // Se estamos a criar, fazemos o POST para a rota 'tarefas.store'.
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
        <!-- O título agora é dinâmico -->
        <h3 class="font-bold text-lg">{{ tarefaToEdit ? 'Editar Tarefa' : 'Adicionar Nova Tarefa' }}</h3>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <!-- O resto do formulário é exatamente o mesmo -->
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

            <div>
                <Label for="data_vencimento">Data de Vencimento (Opcional)</Label>
                <Input id="data_vencimento" v-model="form.data_vencimento" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.data_vencimento" />
            </div>

            <div class="flex items-center justify-end gap-4">
                <Button type="button" variant="ghost" @click="closeForm">Cancelar</Button>
                <Button :disabled="form.processing">Guardar</Button>
            </div>
        </form>
    </div>
</template>