<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { format } from 'date-fns';
import { id as localeID } from 'date-fns/locale';
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  job: Object
});

const page = usePage();
const auth = page.props.auth || {};

function formatDate(dateStr) {
  return format(new Date(dateStr), 'dd MMMM yyyy', { locale: localeID });
}

const showModal = ref(false);
const phone = ref('');
const cvFile = ref(null);
const errors = ref({});

function openApplyForm() {
  if (!auth.user) {
    router.visit('/login');
    return;
  }
  if (auth.user.role === 'applicant') {
    showModal.value = true;
  }
}

function handleFileChange(event) {
  cvFile.value = event.target.files[0];
}

function submitApplication() {
  errors.value = {};
  if (!phone.value) errors.value.phone = 'Nomor telepon wajib diisi';
  if (!cvFile.value) errors.value.cv = 'File CV wajib diupload';
  if (Object.keys(errors.value).length) return;

  const formData = new FormData();
  formData.append('phone', phone.value);
  formData.append('cv', cvFile.value);

  router.post(`/jobs/${props.job.id}/apply`, formData, {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false;
      phone.value = '';
      cvFile.value = null;
    },
    onError: (err) => {
      errors.value = err;
    }
  });
}
</script>

<template>
  <DefaultLayout>
    <div class="max-w-4xl mx-auto px-6 py-12 bg-white rounded-md shadow-md">
      <h1 class="text-4xl font-extrabold text-blue-900 mb-6">{{ job.title }}</h1>

      <div class="flex flex-wrap gap-3 mb-6 text-sm font-medium">
        <span class="bg-blue-200 text-blue-800 px-4 py-1 rounded-full">{{ job.type || 'Full-time' }}</span>
        <span class="bg-indigo-200 text-indigo-900 px-4 py-1 rounded-full">{{ job.location }}</span>
        <span v-if="job.postedDate" class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full">
          Diposting: {{ formatDate(job.postedDate) }}
        </span>
      </div>

      <div class="prose prose-blue max-w-none mb-8 whitespace-pre-line text-gray-700">
        {{ job.description }}
      </div>

      <div class="flex items-center justify-between">
        <a href="/jobs" class="text-blue-600 hover:underline font-semibold">← Kembali ke daftar pekerjaan</a>

        <button
          @click="openApplyForm"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow transition"
        >
          Lamar Pekerjaan
        </button>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <button @click="showModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-lg font-bold">&times;</button>

        <h2 class="text-xl font-semibold mb-4">Form Lamaran</h2>

        <div class="mb-3">
          <label class="block font-medium">Nama</label>
          <input type="text" :value="auth.user.name" readonly class="border px-3 py-2 rounded w-full bg-gray-100" />
        </div>

        <div class="mb-3">
          <label class="block font-medium">Email</label>
          <input type="email" :value="auth.user.email" readonly class="border px-3 py-2 rounded w-full bg-gray-100" />
        </div>

        <div class="mb-3">
          <label class="block font-medium">Nomor Telepon</label>
          <input type="text" v-model="phone" class="border px-3 py-2 rounded w-full" />
          <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
        </div>

        <div class="mb-3">
          <label class="block font-medium">Upload CV (PDF, max 500 KB)</label>
          <input type="file" @change="handleFileChange" accept="application/pdf" />
          <p v-if="errors.cv" class="text-red-600 text-sm mt-1">{{ errors.cv }}</p>
        </div>

        <button @click="submitApplication" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
          Kirim Lamaran
        </button>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
.prose {
  white-space: pre-line;
}
</style>
