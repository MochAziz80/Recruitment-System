<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/register');
}
</script>

<template>
  <DefaultLayout>
    <div class="max-w-md mx-auto mt-20 p-8 bg-white rounded shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Daftar Akun Baru</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label for="name" class="block mb-1 font-semibold">Nama Lengkap</label>
          <input
            id="name"
            type="text"
            v-model="form.name"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autofocus
          />
          <span class="text-red-600 text-sm" v-if="form.errors.name">{{ form.errors.name }}</span>
        </div>

        <div>
          <label for="email" class="block mb-1 font-semibold">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          />
          <span class="text-red-600 text-sm" v-if="form.errors.email">{{ form.errors.email }}</span>
        </div>

        <div>
          <label for="password" class="block mb-1 font-semibold">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <span class="text-red-600 text-sm" v-if="form.errors.password">{{ form.errors.password }}</span>
        </div>

        <div>
          <label for="password_confirmation" class="block mb-1 font-semibold">Konfirmasi Password</label>
          <input
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <span class="text-red-600 text-sm" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</span>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition disabled:opacity-50"
        >
          Daftar
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Sudah punya akun?
        <a href="/login" class="text-blue-600 hover:underline font-semibold">Masuk di sini</a>
      </p>
    </div>
  </DefaultLayout>
</template>
