<script setup>
import { ref, computed, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import {
  PencilIcon,
  TrashIcon,
  XMarkIcon,
  CheckIcon,
  ChevronUpDownIcon,
  UserGroupIcon
} from '@heroicons/vue/24/outline'

const { props } = usePage()
const users = props.users

const showModal = ref(false)
const selectedUser = ref(null)

function openEditModal(user) {
  selectedUser.value = { ...user }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedUser.value = null
}

const isFormValid = computed(() => {
  if (!selectedUser.value) return false
  return (
    selectedUser.value.name.trim() !== '' &&
    selectedUser.value.email.trim() !== '' &&
    selectedUser.value.role.trim() !== ''
  )
})

function updateUser() {
  if (!isFormValid.value) return
  router.put(`/users/${selectedUser.value.id}`, selectedUser.value, {
    onSuccess: () => closeModal()
  })
}

function deleteUser(id) {
  if (confirm('Yakin ingin menghapus user ini?')) {
    router.delete(`/users/${id}`)
  }
}

// Filter dan Sort
const searchQuery = ref('')
const sortAsc = ref(true)

const filteredUsers = computed(() => {
  let result = users.data.filter(user =>
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    user.role.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
  result.sort((a, b) => {
    return sortAsc.value
      ? a.name.localeCompare(b.name)
      : b.name.localeCompare(a.name)
  })
  return result
})

function toggleSort() {
  sortAsc.value = !sortAsc.value
}

const { filters } = usePage().props
const selectedRole = ref(filters.role || '')

watch(selectedRole, (newRole) => {
  router.get('/admin/user', { role: newRole || undefined }, { preserveState: true, replace: true })
})
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <AdminSidebar />

    <main class="flex-1 p-8 space-y-6 ml-45">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
          <UserGroupIcon class="h-8 w-8 text-blue-600 mr-2" />
          Manage Users
        </h1>

        

        <div class="flex items-center space-x-2">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari nama/email/role..."
            class="border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />

          <select
            v-model="selectedRole"
            class="border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none"
            title="Filter Role"
          >
            <option value="">Semua Role</option>
            <option value="administrator">Admin</option>
            <option value="applicant">Applicant</option>
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
              <th class="px-4 py-2 text-left">Name</th>
              <th class="px-4 py-2 text-left">Email</th>
              <th class="px-4 py-2 text-left">Role</th>
              <th class="px-4 py-2 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(user, index) in filteredUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="px-4 py-2 whitespace-nowrap">{{ index + 1 }}</td>
              <td class="px-4 py-2 whitespace-nowrap">{{ user.name }}</td>
              <td class="px-4 py-2 whitespace-nowrap">{{ user.email }}</td>
              <td class="px-4 py-2 whitespace-nowrap capitalize">{{ user.role }}</td>
              <td class="px-4 py-2 whitespace-nowrap flex space-x-2 items-center">
                <button
                  @click="openEditModal(user)"
                  class="text-blue-600 hover:text-blue-800 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                  <PencilIcon class="h-5 w-5" />
                </button>
                <button
                  @click="deleteUser(user.id)"
                  class="text-red-600 hover:text-red-800 p-1 rounded focus:outline-none focus:ring-2 focus:ring-red-400"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Edit -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
      >
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
          <button
            @click="closeModal"
            class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded"
          >
            <XMarkIcon class="h-6 w-6" />
          </button>

          <h2 class="text-xl font-bold mb-4">Edit User</h2>
          <form @submit.prevent="updateUser" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium mb-1">Name</label>
              <input
                id="name"
                v-model="selectedUser.name"
                type="text"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
                autofocus
              />
            </div>
            <div>
              <label for="email" class="block text-sm font-medium mb-1">Email</label>
              <input
                id="email"
                v-model="selectedUser.email"
                type="email"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div>
              <label for="role" class="block text-sm font-medium mb-1">Role</label>
              <select
                id="role"
                v-model="selectedUser.role"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              >
                <option value="administrator">Admin</option>
                <option value="applicant">Applicant</option>
              </select>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="!isFormValid"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <CheckIcon class="inline h-5 w-5 mr-1 -mt-1" /> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>
