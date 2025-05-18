<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

function submit() {
  form.post('/login');
}
</script>

<template>
  <DefaultLayout>
    <div class="max-w-md mx-auto mt-20 p-8 bg-white rounded shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Masuk ke Akun Anda</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold" for="email">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autofocus
          />
          <span class="text-red-600 text-sm" v-if="form.errors.email">{{ form.errors.email }}</span>
        </div>

        <div>
          <label class="block mb-1 font-semibold" for="password">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="current-password"
          />
          <span class="text-red-600 text-sm" v-if="form.errors.password">{{ form.errors.password }}</span>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition disabled:opacity-50"
        >
          Masuk
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Belum punya akun?
        <a href="/register" class="text-blue-600 hover:underline font-semibold">Daftar di sini</a>
      </p>
    </div>
  </DefaultLayout>
</template>
