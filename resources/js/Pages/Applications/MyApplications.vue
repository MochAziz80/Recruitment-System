<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

const props = defineProps({
  applications: Object, 
});
</script>

<template>
  <DefaultLayout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-6">Daftar Lamaran Saya</h1>

      <table class="w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="border border-gray-300 p-2 text-left">Posisi</th>
            <th class="border border-gray-300 p-2 text-left">Tanggal Melamar</th>
            <th class="border border-gray-300 p-2 text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="applications.data.length === 0">
            <td colspan="3" class="text-center p-4 text-gray-600">Belum ada lamaran</td>
          </tr>

          <tr v-for="app in applications.data" :key="app.id" class="hover:bg-gray-50">
            <td class="border border-gray-300 p-2">{{ app.job?.title || 'N/A' }}</td>
            <td class="border border-gray-300 p-2">
              {{ new Date(app.applied_at).toLocaleDateString('id-ID') }}
            </td>
            <td class="border border-gray-300 p-2 capitalize">{{ app.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </DefaultLayout>
</template>
