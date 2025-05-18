<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import {
  PencilIcon,
  TrashIcon,
  ChevronUpDownIcon,
  BriefcaseIcon
} from '@heroicons/vue/24/outline'

const { props } = usePage()
const jobs = props.jobs

// Pencarian dan Filter
const searchQuery = ref('')
const sortAsc = ref(true)
const selectedStatus = ref('')

// Filter dan urutkan
const filteredJobs = computed(() => {
  let result = jobs.filter(job =>
    job.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    job.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  )

  if (selectedStatus.value !== '') {
    const isActiveBool = selectedStatus.value === 'open'
    result = result.filter(job => job.is_active === isActiveBool)
  }

  result.sort((a, b) => {
    return sortAsc.value
      ? a.title.localeCompare(b.title)
      : b.title.localeCompare(a.title)
  })

  return result
})



function toggleSort() {
  sortAsc.value = !sortAsc.value
}

function deleteJob(id) {
  if (confirm('Yakin ingin menghapus lowongan ini?')) {
    router.delete(`/jobs/${id}`)
  }
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <AdminSidebar />

    <main class="flex-1 p-8 space-y-6">

            <!-- DEBUG -->
        <!-- <pre>{{ jobs }}</pre> -->

      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
          <BriefcaseIcon class="h-8 w-8 text-blue-600 mr-2" />
          Manage Jobs
        </h1>
        
        <div class="flex items-center space-x-2">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari judul/desk..."
            class="border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
          <select
            v-model="selectedStatus"
            class="border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
            title="Filter Status"
          >
            <option value="">All Status</option>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
          </select>
          <button
            @click="toggleSort"
            class="flex items-center px-3 py-2 border border-gray-300 rounded hover:bg-gray-100"
            title="Urutkan A-Z / Z-A"
          >
            <ChevronUpDownIcon class="h-5 w-5 text-gray-600" />
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded shadow p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-semibold">#</th>
              <th class="px-4 py-2 text-left">Title</th>
              <th class="px-4 py-2 text-left">Location</th> 
              <th class="px-4 py-2 text-left">Status</th>
              <th class="px-4 py-2 text-left">Posted By</th>
              <th class="px-4 py-2 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(job, index) in filteredJobs" :key="job.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 whitespace-nowrap">{{ index + 1 }}</td>
              <td class="px-4 py-2 whitespace-nowrap">{{ job.title }}</td>
              <td class="px-4 py-2 whitespace-nowrap">{{ job.location }}</td>
              <td class="px-4 py-2 whitespace-nowrap">
                {{ job.is_active ? 'Open' : 'Closed' }}
              </td>
              <td class="px-4 py-2 whitespace-nowrap">{{ job.posted_by?.name ?? 'Unknown' }}</td>

              <td class="px-4 py-2 whitespace-nowrap flex space-x-2 items-center">
                <a
                  :href="`/jobs/${job.id}/edit`"
                  class="text-blue-600 hover:text-blue-800 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                  <PencilIcon class="h-5 w-5" />
                </a>
                <button
                  @click="deleteJob(job.id)"
                  class="text-red-600 hover:text-red-800 p-1 rounded focus:outline-none focus:ring-2 focus:ring-red-400"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>
