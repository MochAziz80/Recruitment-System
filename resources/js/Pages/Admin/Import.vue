<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const file = ref(null)
const loading = ref(false)

const submit = () => {
  if (!file.value) return alert('Please select a file.')
  loading.value = true

  const formData = new FormData()
  formData.append('file', file.value)

  router.post(route('admin.import'), formData, {
    onFinish: () => loading.value = false,
  })
}
</script>

<template>
  <div class="p-6 max-w-xl mx-auto bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Import Data</h1>

    <input
      type="file"
      accept=".xlsx,.xls"
      @change="e => file.value = e.target.files[0]"
      class="block w-full mb-4 border rounded px-3 py-2"
    />

    <button
      @click="submit"
      :disabled="loading"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
    >
      {{ loading ? 'Importing...' : 'Import' }}
    </button>
  </div>
</template>
