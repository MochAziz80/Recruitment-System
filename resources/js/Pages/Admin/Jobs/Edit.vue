<script setup>
import { ref, watch } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import AdminSidebar from '@/Components/AdminSidebar.vue'

const { props } = usePage()
const job = ref(props.job)
const audits = ref(props.audits)

const form = useForm({
  title: job.value.title || '',
  description: job.value.description || '',
  location: job.value.location || '',
  is_active: job.value.is_active || false,
  requirementsText: (job.value.requirements || []).join(', '),
})

watch(job, (newJob) => {
  form.title = newJob.title || ''
  form.description = newJob.description || ''
  form.location = newJob.location || ''
  form.is_active = newJob.is_active || false
  form.requirementsText = (newJob.requirements || []).join(', ')
})

function submit() {
  form.requirements = form.requirementsText
    .split(',')
    .map(s => s.trim())
    .filter(s => s.length > 0)

  form.put(route('admin.jobs.update', job.value.id), {
    onSuccess: () => {
      toast.success('Job updated successfully!', {
        autoClose: 3000,
        position: toast.POSITION.BOTTOM_RIGHT,
      })
    },
    onError: () => {
      toast.error('Failed to update job. Please check the input.', {
        autoClose: 4000,
        position: toast.POSITION.BOTTOM_RIGHT,
      })
    }
  })
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main content -->
    <main class="flex-1 p-6 w-full">
      <h1 class="text-3xl font-bold mb-6">Edit Job - {{ job.title }}</h1>

      <!-- Edit Form -->
      <form @submit.prevent="submit" class="bg-white p-6 rounded shadow max-w-3xl mb-10">
        <div class="mb-4">
          <label for="title" class="block mb-1 font-semibold">Title</label>
          <input
            id="title"
            type="text"
            v-model="form.title"
            class="w-full border border-gray-300 rounded px-3 py-2"
            required
          />
        </div>

        <div class="mb-4">
          <label for="description" class="block mb-1 font-semibold">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            rows="4"
            class="w-full border border-gray-300 rounded px-3 py-2"
            required
          ></textarea>
        </div>

        <div class="mb-4">
          <label for="location" class="block mb-1 font-semibold">Location</label>
          <input
            id="location"
            type="text"
            v-model="form.location"
            class="w-full border border-gray-300 rounded px-3 py-2"
          />
        </div>

        <div class="mb-4">
          <label for="requirements" class="block mb-1 font-semibold">
            Requirements (comma separated)
          </label>
          <input
            id="requirements"
            type="text"
            v-model="form.requirementsText"
            placeholder="Bachelor's degree, 5 years experience, etc."
            class="w-full border border-gray-300 rounded px-3 py-2"
          />
        </div>

        <div class="mb-4 flex items-center gap-2">
          <input
            id="is_active"
            type="checkbox"
            v-model="form.is_active"
            class="rounded border-gray-300"
          />
          <label for="is_active" class="font-semibold">Active</label>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
        >
          Save Changes
        </button>
      </form>

      <!-- Audit Trail -->
      <section>
        <h2 class="text-xl font-bold mb-4 border-b pb-2">Audit Trail</h2>
        <div class="overflow-auto rounded shadow">
          <table class="min-w-full table-auto border border-gray-300 text-sm">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="border px-4 py-2 text-left">User</th>
                <th class="border px-4 py-2 text-left">Event</th>
                <th class="border px-4 py-2 text-left">Changed Fields</th>
                <th class="border px-4 py-2 text-left">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="audit in audits"
                :key="audit.id"
                class="odd:bg-white even:bg-gray-50"
              >
                <td class="border px-4 py-2 font-medium">
                  {{ audit.user ? audit.user.name : 'System' }}
                </td>
                <td class="border px-4 py-2 capitalize">{{ audit.event }}</td>
                <td class="border px-4 py-2">
                  <ul class="space-y-1 list-disc pl-5">
                    <li
                      v-for="(value, key) in audit.new_values"
                      :key="key"
                    >
                      <strong>{{ key }}</strong>: {{ audit.old_values[key] ?? '(new)' }} → {{ value }}
                    </li>
                  </ul>
                </td>
                <td class="border px-4 py-2 text-gray-600">
                  {{ new Date(audit.created_at).toLocaleString() }}
                </td>
              </tr>
              <tr v-if="audits.length === 0">
                <td colspan="4" class="text-center text-gray-500 py-4">
                  No audit data available.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</template>
