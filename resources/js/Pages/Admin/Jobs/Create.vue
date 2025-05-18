<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Konten utama tanpa padding horizontal agar rapat ke sidebar -->
    <main class="flex-1 p-8 md:p-6 lg:p-8">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create New Job</h1>
        <form @submit.prevent="submit" class="space-y-6 bg-white p-6 rounded shadow">

          <!-- Title -->
          <div>
            <label for="title" class="block font-medium text-gray-700 mb-1">Title</label>
            <input
              v-model="form.title"
              id="title"
              type="text"
              class="w-full border border-gray-300 rounded px-3 py-2"
              required
            />
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="form.description"
              id="description"
              class="w-full border border-gray-300 rounded px-3 py-2"
            ></textarea>
          </div>

          <!-- Location -->
          <div>
            <label for="location" class="block font-medium text-gray-700 mb-1">Location</label>
            <input
              v-model="form.location"
              id="location"
              type="text"
              class="w-full border border-gray-300 rounded px-3 py-2"
            />
          </div>

          <!-- Requirements (dynamic) -->
          <div>
            <label class="block font-medium text-gray-700 mb-1">Requirements</label>
            <div class="flex space-x-2 mb-2">
              <input
                v-model="form.newRequirement"
                type="text"
                placeholder="Add requirement"
                class="flex-1 border border-gray-300 rounded px-3 py-2"
                @keyup.enter.prevent="addRequirement"
              />
              <button
                type="button"
                @click="addRequirement"
                class="bg-green-500 text-white px-3 rounded hover:bg-green-600"
                title="Add requirement"
              >
                +
              </button>
            </div>
            <ul class="space-y-1">
              <li
                v-for="(req, index) in form.requirements"
                :key="index"
                class="flex items-center justify-between bg-gray-200 rounded px-3 py-1"
              >
                <span>{{ req }}</span>
                <button
                  type="button"
                  @click="removeRequirement(index)"
                  class="text-red-600 hover:text-red-800 font-bold"
                  title="Remove"
                >
                  &times;
                </button>
              </li>
            </ul>
          </div>

          <!-- Checkbox and Posted At -->
          <div class="flex items-center space-x-4">
            <label class="flex items-center space-x-2">
              <input
                type="checkbox"
                v-model="form.is_active"
                class="form-checkbox"
              />
              <span>Active</span>
            </label>

            <label>
              Posted At:
              <input
                type="date"
                v-model="form.posted_at"
                class="border border-gray-300 rounded px-2 py-1"
              />
            </label>
          </div>

          <button
            type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
          >
            Save
          </button>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'

const form = ref({
  title: '',
  description: '',
  location: '',
  requirements: [],
  newRequirement: '',
  is_active: true,
  posted_at: null,
})

const addRequirement = () => {
  const val = form.value.newRequirement.trim()
  if (val && !form.value.requirements.includes(val)) {
    form.value.requirements.push(val)
  }
  form.value.newRequirement = ''
}

const removeRequirement = (index) => {
  form.value.requirements.splice(index, 1)
}

const submit = () => {
  router.post('/admin/jobs', form.value)
}
</script>
