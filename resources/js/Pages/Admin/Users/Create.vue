<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminSidebar from '@/Components/AdminSidebar.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const role = ref('applicant');
const phone = ref('');
const cvFile = ref(null);
const errors = ref({});

function handleFileChange(event) {
  cvFile.value = event.target.files[0];
}

function submit() {
  errors.value = {};

  const formData = new FormData();
  formData.append('name', name.value);
  formData.append('email', email.value);
  formData.append('password', password.value);
  formData.append('password_confirmation', password_confirmation.value);
  formData.append('role', role.value);
  formData.append('phone', phone.value);
  if (cvFile.value) {
    formData.append('cv', cvFile.value);
  }

  router.post('/admin/user/generate', formData, {
    preserveScroll: true,
    onError: (err) => {
      errors.value = err;
    },
    onSuccess: () => {
      name.value = '';
      email.value = '';
      password.value = '';
      password_confirmation.value = '';
      role.value = 'applicant';
      phone.value = '';
      cvFile.value = null;
      errors.value = {};
      toast.success('User berhasil dibuat!', {
        position: "top-right",
        autoClose: 3000,
      });
    },
  });
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <main class="flex-1 p-8 max-w-3xl mx-auto bg-white rounded-lg shadow-md">
      <h1 class="text-3xl font-semibold mb-8 text-gray-800">Buat User Baru</h1>

      <form @submit.prevent="submit" enctype="multipart/form-data" novalidate class="space-y-6">
        <!-- Nama -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="name">Nama</label>
          <input
            id="name"
            type="text"
            v-model="name"
            placeholder="Masukkan nama lengkap"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
          <input
            id="email"
            type="email"
            v-model="email"
            placeholder="contoh@domain.com"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="password">Password</label>
          <input
            id="password"
            type="password"
            v-model="password"
            placeholder="Minimal 8 karakter"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="password_confirmation">Konfirmasi Password</label>
          <input
            id="password_confirmation"
            type="password"
            v-model="password_confirmation"
            placeholder="Ulangi password"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
        </div>

        <!-- Role -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="role">Role</label>
          <select
            id="role"
            v-model="role"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          >
            <option value="applicant">Applicant</option>
            <option value="administrator">Administrator</option>
          </select>
          <p v-if="errors.role" class="mt-1 text-sm text-red-600">{{ errors.role }}</p>
        </div>

        <!-- Nomor Telepon -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="phone">Nomor Telepon</label>
          <input
            id="phone"
            type="text"
            v-model="phone"
            placeholder="08123456789"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
        </div>

        <!-- Upload CV -->
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="cv">Upload CV (PDF, max 500 KB)</label>
          <input
            id="cv"
            type="file"
            @change="handleFileChange"
            accept="application/pdf"
            class="block w-full text-gray-600 cursor-pointer file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0
                   file:text-sm file:font-semibold
                   file:bg-blue-50 file:text-blue-700
                   hover:file:bg-blue-100
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <p v-if="errors.cv_path" class="mt-1 text-sm text-red-600">{{ errors.cv_path }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition"
        >
          Buat User
        </button>
      </form>
    </main>
  </div>
</template>
