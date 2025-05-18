<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
  >
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
      <button
        @click="goBack"
        class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded"
      >
        ✕
      </button>

      <h2 class="text-xl font-bold mb-4">Tambah User</h2>
      <form @submit.prevent="addUser" class="space-y-4">
        <input v-model="user.name" placeholder="Nama" required />
        <input v-model="user.email" type="email" placeholder="Email" required />
        <select v-model="user.role" required>
          <option value="administrator">Admin</option>
          <option value="applicant">Applicant</option>
        </select>
        <button type="submit">Simpan</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const user = ref({ name: '', email: '', role: 'applicant' })

function goBack() {
  router.back()
}

function addUser() {
  router.post('/users', user.value, {
    onSuccess: () => router.push('/admin/users')
  })
}
</script>
