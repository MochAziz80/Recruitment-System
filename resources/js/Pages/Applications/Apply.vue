<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const authUser = page.props.auth?.user || {};

const phone = ref('');
const cvFile = ref(null);
const errors = ref({});

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    if (file.type !== 'application/pdf') {
      errors.value.cv = 'File harus berupa PDF';
      cvFile.value = null;
    } else if (file.size > 500 * 1024) {
      errors.value.cv = 'Ukuran file maksimal 500 KB';
      cvFile.value = null;
    } else {
      errors.value.cv = null;
      cvFile.value = file;
    }
  }
}

function submitApplication() {
  errors.value = {};

  if (!phone.value.trim()) {
    errors.value.phone = 'Nomor telepon wajib diisi';
  }
  if (!cvFile.value) {
    errors.value.cv = 'File CV wajib diupload';
  }

  if (Object.values(errors.value).some(Boolean)) return;

  const formData = new FormData();
  formData.append('phone', phone.value.trim());
  formData.append('cv', cvFile.value);

  router.post(`/jobs/${page.props.job.id}/apply`, formData, {
    preserveScroll: true,
    onError: (err) => {
      errors.value = err;
    },
  });
}
</script>

<template>
  <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Lamar Pekerjaan: <span class="font-normal">{{ page.props.job.title }}</span></h2>

    <div class="mb-5">
      <label class="block mb-1 font-medium text-gray-700">Nama</label>
      <input
        type="text"
        :value="authUser.name"
        readonly
        class="w-full border border-gray-300 rounded-md bg-gray-100 px-3 py-2 text-gray-700 cursor-not-allowed"
      />
    </div>

    <div class="mb-5">
      <label class="block mb-1 font-medium text-gray-700">Email</label>
      <input
        type="email"
        :value="authUser.email"
        readonly
        class="w-full border border-gray-300 rounded-md bg-gray-100 px-3 py-2 text-gray-700 cursor-not-allowed"
      />
    </div>

    <div class="mb-5">
      <label class="block mb-1 font-medium text-gray-700" for="phone">Nomor Telepon</label>
      <input
        id="phone"
        type="text"
        v-model="phone"
        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        placeholder="Masukkan nomor telepon"
      />
      <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
    </div>

    <div class="mb-6">
      <label class="block mb-1 font-medium text-gray-700" for="cv">Upload CV (PDF, max 500 KB)</label>
      <input
        id="cv"
        type="file"
        accept="application/pdf"
        @change="handleFileChange"
        class="w-full text-gray-700"
      />
      <p v-if="errors.cv" class="text-red-600 text-sm mt-1">{{ errors.cv }}</p>
    </div>

    <button
      @click="submitApplication"
      class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-md transition"
    >
      Kirim Lamaran
    </button>
  </div>
</template>
