<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ChevronDown,
    Github,
    LogOut,
    Menu,
    Settings,
    Sun,
    Moon
} from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { useColorMode } from '@vueuse/core';
// REMOVEMOS A IMPORTAÇÃO DO NavLink DAQUI

const mode = useColorMode();
</script>

<template>
    <div class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]">
        <div class="hidden border-r bg-muted/40 md:block">
            <div class="flex h-full max-h-screen flex-col gap-2">
                <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                    <Link href="/" class="flex items-center gap-2 font-semibold">
                        <span class="">Dooit</span>
                    </Link>
                </div>
                <div class="flex-1">
                    <nav class="grid items-start px-2 text-sm font-medium lg:px-4">
                        <!-- **** MUDANÇA AQUI: Substituímos <NavLink> por <Link> **** -->
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                            :class="{ 'bg-muted text-primary': route().current('dashboard') }"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :href="route('tarefas.index')"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                            :class="{ 'bg-muted text-primary': route().current('tarefas.index') }"
                        >
                            Minhas Tarefas
                        </Link>
                    </nav>
                </div>
                <div class="mt-auto p-4">
                    <a href="https://github.com/inovcorp-regiane/dooit" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary">
                        <Github class="w-4 h-4" />
                        Github Repo
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <header class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button
                            variant="outline"
                            size="icon"
                            class="shrink-0 md:hidden"
                        >
                            <Menu class="h-5 w-5" />
                            <span class="sr-only">Toggle navigation menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <Link
                                href="#"
                                class="flex items-center gap-2 text-lg font-semibold"
                            >
                                <span class="sr-only">Dooit</span>
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
                        <div class="mt-auto">
                            <a href="https://github.com/inovcorp-regiane/dooit" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary">
                                <Github class="w-4 h-4" />
                                Github Repo
                            </a>
                        </div>
                    </SheetContent>
                </Sheet>
                <div class="w-full flex-1" />
                <Button
                    variant="outline"
                    size="icon"
                    class="h-8 w-8"
                    @click="mode === 'light' ? mode = 'dark' : mode = 'light'"
                >
                    <Sun class="h-4 w-4" v-if="mode === 'light'" />
                    <Moon class="h-4 w-4" v-if="mode === 'dark'" />
                </Button>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="secondary" size="icon" class="rounded-full">
                            <span class="uppercase">
                                {{ $page.props.auth.user.name.substring(0, 2) }}
                            </span>
                            <span class="sr-only">Toggle user menu</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>My Account</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem as-child>
                            <Link :href="route('profile.edit')">
                                Settings
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem as-child>
                            <Link :href="route('logout')" method="post">
                                Log out
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
            <main class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>