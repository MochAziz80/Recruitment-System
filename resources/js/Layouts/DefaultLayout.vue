<template>
    <div class="min-h-screen bg-blue-50 flex flex-col">
        <!-- Navbar -->
        <header class="bg-blue-700 shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center">
                <!-- Logo / Judul -->
                <h1 class="text-white text-2xl font-bold cursor-pointer hover:text-blue-300 transition"
                    @click="$inertia.visit('/')">
                    RekJa
                </h1>

                <!-- Navigation links -->
                <nav class="ml-auto flex items-center space-x-6 text-blue-200 font-semibold">
                    <Link href="/" class="hover:text-white"
                        :class="{ 'text-white border-b-2 border-white pb-1': isActive('/') }">
                    Home
                    </Link>
                    <Link href="/jobs" class="hover:text-white"
                        :class="{ 'text-white border-b-2 border-white pb-1': isActive('/jobs') }">
                    Jobs
                    </Link>

                    <!-- Jika belum login -->
                    <template v-if="!auth.user">
                        <Link href="/login" class="hover:text-white"
                            :class="{ 'text-white border-b-2 border-white pb-1': isActive('/login') }">
                        Sign In
                        </Link>
                        <Link href="/register" class="hover:text-white"
                            :class="{ 'text-white border-b-2 border-white pb-1': isActive('/register') }">
                        Sign Up
                        </Link>
                    </template>

                    <!-- Jika sudah login -->
                    <template v-else>
                        <!-- Menu Dashboard khusus Admin -->
                        <Link v-if="auth.user.role === 'admin' || auth.user.role === 'administrator'"
                            href="/admin/dashboard" class="hover:text-white"
                            :class="{ 'text-white border-b-2 border-white pb-1': isActive('/admin/dashboard') }">
                        Dashboard Admin
                        </Link>

                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="flex items-center space-x-2 text-white focus:outline-none">
                                <span>{{ auth.user.name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div v-show="dropdownOpen"
                                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-2 text-gray-700">
                                <Link href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</Link>
                                <form method="POST" action="/logout"
                                    class="block px-4 py-2 hover:bg-gray-100 cursor-pointer" @submit.prevent="logout">
                                    <button type="submit" class="w-full text-left">Logout</button>
                                </form>
                            </div>
                        </div>

                    </template>
                </nav>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Optional Footer -->
        <footer class="bg-indigo-700 text-white text-center py-4">
            &copy; {{ new Date().getFullYear() }} Recruitment System. All rights reserved.
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const auth = page.props.auth;

const dropdownOpen = ref(false);

function isActive(path) {
    return page.url === path || page.url.startsWith(path + '/');
}

function logout() {
    router.post('/logout');
}
</script>
