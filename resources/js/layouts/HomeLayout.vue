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
                <footer class="py-12 lg:py-16 border-t border-neutral-100">
      <div class="w-[96%] max-w-7xl mx-auto">
        <div
          class="border-b border-neutral-100 pb-8 lg:pb-16 flex justify-between flex-col lg:flex-row items-center lg:items-start"
        >
          <div
            class="space-y-8 pb-8 border-b border-neutral-100 lg:pb-0 lg:border-none w-full flex flex-col lg:block items-center"
          >
            <img src="../../../public/images/logo-principal.png" style="float:center;width:130px;height:90px;" alt="ADA Motor Center" class="w-fit" />
            <!--<a href="#" class="hover:text-cyan-600">ADA Motor Center</a>-->
            <ul
              class="flex gap-x-8 text-xs text-gray-500 flex-col  gap-y-6 text-center lg:text-start"
            >
              <li>
                <a href="#" class="hover:text-cyan-600">Products & Service</a>
              </li>
              <li><a href="#" class="hover:text-cyan-600">Resources</a></li>
              <li><a href="#" class="hover:text-cyan-600">Contact</a></li>
              <li><a href="#" class="hover:text-cyan-600">About</a></li>
            </ul>
            <!-- Social icons -->
            <div class="flex items-center gap-x-4 text-gray-700">
              <!-- Facebook icon -->
              <a href="#" class="hover:text-cyan-600 cursor-pointer">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="24"
                  height="24"
                  viewBox="0 0 30 30"
                  fill="currentColor"
                >
                  <path
                    d="M15,3C8.373,3,3,8.373,3,15c0,6.016,4.432,10.984,10.206,11.852V18.18h-2.969v-3.154h2.969v-2.099c0-3.475,1.693-5,4.581-5 c1.383,0,2.115,0.103,2.461,0.149v2.753h-1.97c-1.226,0-1.654,1.163-1.654,2.473v1.724h3.593L19.73,18.18h-3.106v8.697 C22.481,26.083,27,21.075,27,15C27,8.373,21.627,3,15,3z"
                  ></path>
                </svg>
              </a>
              <!-- Instagram icon -->
              <a href="#" class="hover:text-cyan-600 cursor-pointer">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="24"
                  height="24"
                  viewBox="0 0 32 32"
                  fill="currentColor"
                >
                  <path
                    d="M 11.46875 5 C 7.917969 5 5 7.914063 5 11.46875 L 5 20.53125 C 5 24.082031 7.914063 27 11.46875 27 L 20.53125 27 C 24.082031 27 27 24.085938 27 20.53125 L 27 11.46875 C 27 7.917969 24.085938 5 20.53125 5 Z M 11.46875 7 L 20.53125 7 C 23.003906 7 25 8.996094 25 11.46875 L 25 20.53125 C 25 23.003906 23.003906 25 20.53125 25 L 11.46875 25 C 8.996094 25 7 23.003906 7 20.53125 L 7 11.46875 C 7 8.996094 8.996094 7 11.46875 7 Z M 21.90625 9.1875 C 21.402344 9.1875 21 9.589844 21 10.09375 C 21 10.597656 21.402344 11 21.90625 11 C 22.410156 11 22.8125 10.597656 22.8125 10.09375 C 22.8125 9.589844 22.410156 9.1875 21.90625 9.1875 Z M 16 10 C 12.699219 10 10 12.699219 10 16 C 10 19.300781 12.699219 22 16 22 C 19.300781 22 22 19.300781 22 16 C 22 12.699219 19.300781 10 16 10 Z M 16 12 C 18.222656 12 20 13.777344 20 16 C 20 18.222656 18.222656 20 16 20 C 13.777344 20 12 18.222656 12 16 C 12 13.777344 13.777344 12 16 12 Z"
                  ></path>
                </svg>
              </a>
              <!-- X icon -->
              <a href="#" class="hover:text-cyan-600 cursor-pointer">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                >
                  <path
                    d="M 2.3671875 3 L 9.4628906 13.140625 L 2.7402344 21 L 5.3808594 21 L 10.644531 14.830078 L 14.960938 21 L 21.871094 21 L 14.449219 10.375 L 20.740234 3 L 18.140625 3 L 13.271484 8.6875 L 9.2988281 3 L 2.3671875 3 z M 6.2070312 5 L 8.2558594 5 L 18.033203 19 L 16.001953 19 L 6.2070312 5 z"
                  ></path>
                </svg>
              </a>
              <!-- LinkedIn icon -->
              <a href="#" class="hover:text-cyan-600 cursor-pointer">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="24"
                  height="24"
                  viewBox="0 0 30 30"
                  fill="currentColor"
                >
                  <path
                    d="M24,4H6C4.895,4,4,4.895,4,6v18c0,1.105,0.895,2,2,2h18c1.105,0,2-0.895,2-2V6C26,4.895,25.105,4,24,4z M10.954,22h-2.95 v-9.492h2.95V22z M9.449,11.151c-0.951,0-1.72-0.771-1.72-1.72c0-0.949,0.77-1.719,1.72-1.719c0.948,0,1.719,0.771,1.719,1.719 C11.168,10.38,10.397,11.151,9.449,11.151z M22.004,22h-2.948v-4.616c0-1.101-0.02-2.517-1.533-2.517 c-1.535,0-1.771,1.199-1.771,2.437V22h-2.948v-9.492h2.83v1.297h0.04c0.394-0.746,1.356-1.533,2.791-1.533 c2.987,0,3.539,1.966,3.539,4.522V22z"
                  ></path>
                </svg>
              </a>
            </div>
            <!-- Social icons -->
          </div>
        </div>
        <div class="pt-8 flex justify-between flex-col gap-y-4 items-center">
          <ul class="flex gap-x-8 text-xs text-gray-500">
            <li><a href="#" class="hover:text-cyan-600">English</a></li>
            <li><a href="#" class="hover:text-cyan-600">Privacy</a></li>
            <li><a href="#" class="hover:text-cyan-600">Legal</a></li>
          </ul>
          <p class="text-xs text-gray-400">
            © 2025 ADA Motor Center. All Rights Reserved.
          </p>
        </div>
      </div>
    </footer>
</div>
</div>
</div>
</template>
