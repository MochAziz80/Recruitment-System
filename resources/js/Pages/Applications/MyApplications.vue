<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

const props = defineProps({
  applications: Object,
});
</script>

<template>
  <DefaultLayout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
      <h1 class="text-3xl font-extrabold mb-8 text-blue-900">Daftar Lamaran Saya</h1>

      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-blue-100 text-blue-900">
            <th class="p-3 text-left font-semibold">Posisi</th>
            <th class="p-3 text-left font-semibold">Tanggal Melamar</th>
            <th class="p-3 text-left font-semibold">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="applications.data.length === 0">
            <td colspan="3" class="text-center py-6 text-gray-500 italic">Belum ada lamaran</td>
          </tr>

          <tr v-for="app in applications.data" :key="app.id" class="hover:bg-blue-50 transition-colors">
            <td class="py-3 px-4 border-b border-gray-200 font-medium text-gray-800">
              {{ app.job?.title || 'N/A' }}
            </td>
            <td class="py-3 px-4 border-b border-gray-200 text-gray-600">
              {{ new Date(app.applied_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
            </td>
            <td class="py-3 px-4 border-b border-gray-200">
              <span
                :class="{
                  'text-green-700 bg-green-100 px-2 py-1 rounded font-semibold': app.status === 'accepted',
                  'text-yellow-700 bg-yellow-100 px-2 py-1 rounded font-semibold': app.status === 'pending',
                  'text-red-700 bg-red-100 px-2 py-1 rounded font-semibold': app.status === 'rejected',
                  'text-gray-700 bg-gray-100 px-2 py-1 rounded font-semibold': !['accepted','pending','rejected'].includes(app.status),
                }"
                class="capitalize"
              >
                {{ app.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </DefaultLayout>
</template>
