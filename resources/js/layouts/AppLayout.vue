<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Github, Menu, Sun, Moon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useColorMode } from '@vueuse/core';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const page = usePage();
const mode = useColorMode();

// fallback seguro para evitar erro quando props não vierem
const taskCounts = computed(() => (page.props.task_counts ?? { pendente: 0, concluida: 0 }));
const user = computed(() => page.props.auth?.user ?? null);

// rota ativa
const isRouteActive = (routeName: string) => {
    try {
        return route().current(routeName);
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]">
        <!-- BARRA LATERAL -->
        <div class="hidden border-r bg-muted/40 md:block">
            <div class="flex h-full max-h-screen flex-col">
                <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                    <Link href="/" class="flex items-center gap-2 font-semibold">
                        <span>Dooit</span>
                    </Link>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <nav class="grid items-start px-2 text-sm font-medium lg:px-4 py-4">
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                            :class="{ 'bg-muted text-primary': isRouteActive('dashboard') }"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :href="route('tarefas.index')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                            :class="{ 'bg-muted text-primary': isRouteActive('tarefas.index') }"
                        >
                            Minhas Tarefas
                        </Link>

                        <!-- RESUMO -->
                        <div class="mt-8 px-3">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Resumo
                            </h3>
                            <div class="space-y-2">
                                <Link
                                    :href="route('tarefas.index', { estado: 'pendente' })"
                                    class="flex justify-between items-center text-sm text-gray-600 hover:text-primary transition-colors p-2 rounded-md hover:bg-gray-100"
                                >
                                    <span>Pendentes</span>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ taskCounts.pendente }}
                                    </span>
                                </Link>
                                <Link
                                    :href="route('tarefas.index', { estado: 'concluida' })"
                                    class="flex justify-between items-center text-sm text-gray-600 hover:text-primary transition-colors p-2 rounded-md hover:bg-gray-100"
                                >
                                    <span>Concluídas</span>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ taskCounts.concluida }}
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- FOOTER -->
                <div class="mt-auto p-4 border-t">
                    <a
                        href="https://github.com/inovcorp-regiane/dooit"
                        target="_blank"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                    >
                        <Github class="w-4 h-4" />
                        Github Repo
                    </a>
                </div>
            </div>
        </div>

        <!-- CONTEÚDO PRINCIPAL -->
        <div class="flex flex-col">
            <header class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6">
                <Sheet>
                    <SheetTrigger asChild>
                        <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <Link href="/" class="flex items-center gap-2 text-lg font-semibold">
                                <span>Dooit</span>
                            </Link>
                            <Link
                                :href="route('dashboard')"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('tarefas.index')"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                Minhas Tarefas
                            </Link>
                        </nav>
                    </SheetContent>
                </Sheet>

                <div class="w-full flex-1" />

                <!-- Botão Dark/Light -->
                <Button
                    variant="outline"
                    size="icon"
                    class="h-8 w-8"
                    @click="mode.value = mode.value === 'light' ? 'dark' : 'light'"
                >
                    <Sun class="h-4 w-4" v-if="mode.value === 'light'" />
                    <Moon class="h-4 w-4" v-if="mode.value === 'dark'" />
                </Button>

                <!-- MENU USER -->
                <div v-if="user">
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button variant="secondary" size="icon" class="rounded-full">
                                <span class="uppercase">{{ user.name.substring(0, 2) }}</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>{{ user.name }}</DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem asChild>
                                <Link :href="route('profile.edit')">Definições</Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem asChild>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full text-left"
                                >
                                    Sair
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <!-- MENU QUANDO NÃO ESTÁ AUTENTICADO -->
                <div v-else>
                    <Link :href="route('login')">
                        <Button variant="default">Entrar</Button>
                    </Link>
                </div>
            </header>

            <main
                class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 bg-slate-50"
            >
                <slot />
            </main>
        </div>
    </div>
</template>
