<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'

const { props } = usePage()
const application = props.application

const form = useForm({
  status: application.status || 'pending',
  notes: application.notes || '',
})

function submit() {
  form.put(route('admin.applicants.updateStatus', application.id), {
    onSuccess: () => alert('Status updated!'),
  })
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <AdminSidebar />

    <main class="flex-1 p-8 max-w-3xl mx-auto">
      <h1 class="text-3xl font-bold mb-6">Applicant Detail</h1>

      <section class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-2">Applicant Info</h2>
        <p><strong>Name:</strong> {{ application.user.name }}</p>
        <p><strong>Email:</strong> {{ application.user.email }}</p>
        <p><strong>Applied At:</strong> {{ new Date(application.applied_at).toLocaleString() }}</p>
      </section>

      <section class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-2">Job Info</h2>
        <p><strong>Title:</strong> {{ application.job.title }}</p>
        <p><strong>Description:</strong> {{ application.job.description }}</p>
        <p><strong>Location:</strong> {{ application.job.location }}</p>
      </section>

      <section class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-2">Application Status</h2>
        <p><strong>Current Status:</strong> {{ application.status }}</p>
        <p><strong>Notes:</strong> {{ application.notes ?? '-' }}</p>
      </section>

      <section class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Update Status</h2>
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label for="status" class="block font-medium mb-1">Status</label>
            <select v-model="form.status" id="status" class="border rounded px-3 py-2 w-full">
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>

          <div>
            <label for="notes" class="block font-medium mb-1">Notes</label>
            <textarea
              id="notes"
              v-model="form.notes"
              class="border rounded px-3 py-2 w-full"
              rows="4"
              placeholder="Optional notes about the application"
            ></textarea>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
          >
            Update Status
          </button>
        </form>
      </section>
    </main>
  </div>
</template>
