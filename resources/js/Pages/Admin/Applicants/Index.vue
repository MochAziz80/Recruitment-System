<script setup>
import { ref } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import { EyeIcon, DocumentTextIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const { props } = usePage()
const applications = ref(props.applications)

const showModal = ref(false)
const selectedCV = ref(null)

function openCV(cvPath) {
        if (!cvPath) return
    // pastikan hanya nama file yang dioper
    const filename = cvPath.split('/').pop()
    selectedCV.value = `/preview-cv/${filename}`
    showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedCV.value = null
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />

    <main class="flex-1 p-8 max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">📄 Applicants</h1>

      <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold tracking-wider">
            <tr>
              <th class="px-6 py-3 text-left">Applicant</th>
              <th class="px-6 py-3 text-left">Job Title</th>
              <th class="px-6 py-3 text-left">Status</th>
              <th class="px-6 py-3 text-left">Applied At</th>
              <th class="px-6 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="app in applications" :key="app.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ app.user.name }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ app.job.title }}</td>
              <td class="px-6 py-4 text-sm capitalize">
                <span
                  :class="{
                    'bg-green-100 text-green-700': app.status === 'approved',
                    'bg-yellow-100 text-yellow-700': app.status === 'pending',
                    'bg-red-100 text-red-700': app.status === 'rejected',
                  }"
                  class="px-2 py-1 rounded-full text-xs font-medium"
                >
                  {{ app.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ new Date(app.applied_at).toLocaleString() }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 flex gap-3 items-center">
                <Link
                  :href="route('admin.applicants.show', app.id)"
                  class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800"
                >
                  <EyeIcon class="w-5 h-5" />
                  View
                </Link>

                <button
                  @click="openCV(app.user.cv_path)"
                  :disabled="!app.user.cv_path"
                  class="flex items-center gap-1 text-green-600 hover:text-green-800 disabled:text-gray-400"
                  title="Quick View CV"
                >
                  <DocumentTextIcon class="w-5 h-5" />
                  CV
                </button>
              </td>
            </tr>
            <tr v-if="applications.length === 0">
              <td colspan="5" class="text-center py-8 text-gray-500 italic">No applicants found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal CV -->
      <transition name="fade">
        <div
  v-if="showModal"
  class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-6"
  @click.self="closeModal"
>
  <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl max-h-[95vh] h-[95vh] flex flex-col overflow-hidden">
    <header class="flex justify-between items-center px-5 py-3 border-b">
      <h2 class="text-lg font-semibold text-gray-800">CV Preview</h2>
      <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
        <XMarkIcon class="w-6 h-6" />
      </button>
    </header>

    <iframe
      v-if="selectedCV"
      :src="selectedCV"
      class="flex-1 w-full"
      frameborder="0"
    ></iframe>
  </div>
</div>

      </transition>
    </main>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
