<script setup lang="ts">
import { ref } from 'vue';
import '../../css/carousel.css';
import { Head, Link } from '@inertiajs/vue3';
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
  } from '@/components/ui/dropdown-menu';
import { useI18n } from 'vue-i18n';

interface Props {
    canLogin?: boolean;
    canRegister?: boolean;
}

const { locale } = useI18n();
const isSidebarOpen = ref(false);

defineProps<Props>();

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const changeLanguage = (lang: string) => {
    locale.value = lang;
};
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black">
        <div class="relative min-h-screen flex flex-col items-center justify-cente">
            <div class="relative w-full">
                <header class="sticky top-0 z-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-8 items-center gap-2 px-12 py-8 bg-primary text-black text-xs lg:text-sm">
                    <div class="flex lg:justify-center cols-span-2 md:col-span-1">
                        <a href="/" class="font-helvetica-bold text-lg">ADA Motor Center</a>
                    </div>
                    <nav class="-mx-3 hidden lg:flex justify-end relative col-span-7">
                        <div class="relative flex uppercase">
                            <a href="/" class="px-3 py-2 font-helvetica-bold hover:text-black/70">
                                ADA Motor Center
                            </a>
                            <a href="/vehicles" class="px-3 py-2 font-helvetica-bold hover:text-black/70">
                                {{ $t('vehicles') }}
                            </a>
                            <a href="/auctions" class="px-3 py-2 font-helvetica-bold hover:text-black/70">
                                {{ $t('broker') }}
                            </a>
                            <a href="/" class="px-3 py-2 font-helvetica-bold hover:text-black/70">
                                {{ $t('about_us') }}
                            </a>
                            <a href="/" class="px-3 py-2 font-helvetica-bold hover:text-black/70">
                                {{ $t('blog') }}
                            </a>
                            <div class="flex px-2">
                                <button v-if="locale !== 'en'" @click="changeLanguage('en')">
                                        <i class="fi fi-us"></i>
                                </button>
                                <button v-if="locale !== 'es'" @click="changeLanguage('es')">
                                    <i class="fi fi-es"></i>
                                </button>
                            </div>
                        </div>
                        
                        <DropdownMenu>
                            <DropdownMenuTrigger 
                                class="rounded-md px-3 py-2 text-black bg-primary ring-1 font-helvetica-bold ring-transparent transition hover:text-black/70 focus:outline-none"
                            >
                                CUENTA ▼
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <template v-if="$page.props.auth.user">
                                    <DropdownMenuItem>
                                        <a :href="route('dashboard')" class="block w-full px-4 py-2 text-left">
                                            Dashboard
                                        </a>
                                    </DropdownMenuItem>
                                </template>
                                <template v-else>
                                    <DropdownMenuItem>
                                        <a :href="route('login')" class="block w-full px-4 py-2 text-left">
                                            Log in
                                        </a>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <a :href="route('register')" class="block w-full px-4 py-2 text-left">
                                            Register
                                        </a>
                                    </DropdownMenuItem>
                                </template>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </nav>

                    <button @click="toggleSidebar" class="lg:hidden p-2 focus:outline-none flex justify-end text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l16 0" /></svg>
                    </button>
                </header>

                <aside
                    class="fixed inset-y-0 left-0 z-20 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out"
                    :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                >
                    <div class="flex justify-between items-center p-4 border-b">
                        <h2 class="text-lg font-bold">                        
                            <a href="/" class="font-helvetica-bold text-lg">ADA Motor Center</a>
                        </h2>
                        <button @click="toggleSidebar" class="p-2 text-gray-600">
                            ✖
                        </button>
                    </div>

                    <nav class="flex flex-col p-4 space-y-3">
                        <a href="/" class="py-2 px-3 rounded hover:bg-gray-200">{{ $t('vehicles') }}</a>
                        <a href="/" class="py-2 px-3 rounded hover:bg-gray-200">{{ $t('broker') }}</a>
                        <a href="/" class="py-2 px-3 rounded hover:bg-gray-200">{{ $t('about_us') }}</a>
                        <a href="/" class="py-2 px-3 rounded hover:bg-gray-200">{{ $t('blog') }}</a>

                        <div class="flex mt-4 px-3">
                            <button v-if="locale !== 'en'" @click="changeLanguage('en')">
                                <span>Cambia el lenguaje</span><i class="fi fi-us"></i>
                            </button>
                            <button v-if="locale !== 'es'" @click="changeLanguage('es')">
                                <span>Change Langauge</span><i class="fi fi-es"></i>
                            </button>
                        </div>

                        <DropdownMenu>
                            <DropdownMenuTrigger 
                                class="rounded-md px-3 py-2 text-black ring-1 font-helvetica-bold ring-transparent transition hover:text-black/70 focus:outline-none"
                            >
                                CUENTA ▼
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <template v-if="$page.props.auth.user">
                                    <DropdownMenuItem>
                                        <a :href="route('dashboard')" class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                    </DropdownMenuItem>
                                </template>
                                <template v-else>
                                    <DropdownMenuItem>
                                        <a :href="route('login')" class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                            Log in
                                        </a>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <a :href="route('register')" class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                            Register
                                        </a>
                                    </DropdownMenuItem>
                                </template>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </nav>
                </aside>

                <div
                    v-show="isSidebarOpen"
                    class="fixed inset-0 bg-black bg-opacity-50"
                    @click="toggleSidebar">
                </div>

                <main class="">
                    <slot></slot> 
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                </footer>
            </div>
        </div>
    </div>
</template>