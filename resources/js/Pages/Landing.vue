<script setup>
import { Link } from '@inertiajs/vue3';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { ref, onMounted } from 'vue';

const features = [
  { 
    icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 
    title: 'Mudah Digunakan', 
    description: 'Antarmuka yang intuitif memudahkan pengelolaan rekrutmen' 
  },
  { 
    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 
    title: 'Hemat Waktu', 
    description: 'Otomatisasi proses seleksi dan pemberitahuan' 
  },
  { 
    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 
    title: 'Analisis Canggih', 
    description: 'Dapatkan wawasan mendalam tentang proses rekrutmen Anda' 
  },
  { 
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 
    title: 'Kolaborasi Tim', 
    description: 'Bekerja bersama dengan tim HR Anda secara efisien' 
  }
];

const testimonials = [
  {
    name: 'Sarah Wijaya',
    position: 'HR Manager',
    company: 'Tech Innovate',
    content: 'Sistem ini telah mengubah cara kami merekrut talenta. Proses jadi lebih cepat dan lebih efektif!'
  },
  {
    name: 'Budi Santoso',
    position: 'Direktur SDM',
    company: 'Maju Bersama',
    content: 'Dengan menggunakan sistem ini, kami berhasil mengurangi waktu rekrutmen hingga 40%. Sangat direkomendasikan!'
  },
  {
    name: 'Anita Kusuma',
    position: 'Recruitment Specialist',
    company: 'Global Solutions',
    content: 'Dashboard analitis memberikan kami wawasan yang sebelumnya tidak pernah kami miliki. Pengambilan keputusan jadi lebih baik.'
  }
];

const isVisible = ref(false);

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 100);
});
</script>

<template>
  <DefaultLayout>
    <!-- Hero Section -->
    <main :class="['transition-opacity duration-1000', isVisible ? 'opacity-100' : 'opacity-0']">
      <div class="bg-gradient-to-r from-blue-600 to-indigo-800 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Animated Shapes Background -->
          <div class="absolute inset-0 overflow-hidden opacity-20">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full filter blur-3xl animate-float"></div>
            <div class="absolute top-1/2 right-0 w-80 h-80 bg-blue-400 rounded-full filter blur-3xl animate-float-delay"></div>
            <div class="absolute bottom-0 left-1/3 w-64 h-64 bg-purple-500 rounded-full filter blur-3xl animate-float-slow"></div>
          </div>
          
          <div class="relative py-24 sm:py-32 flex flex-col items-center text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-tight mb-6 max-w-4xl">
              Transformasi Rekrutmen Talent untuk <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-300 to-pink-400">Bisnis Anda</span>
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mb-10">
              Platform rekrutmen modern yang menyederhanakan proses perekrutan, menghemat waktu, dan membantu Anda menemukan kandidat terbaik dengan cepat.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-2">
              <Link
                href="/login"
                class="bg-white text-indigo-700 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-lg"
              >
                Masuk Sekarang
              </Link>
              <Link
                href="/register"
                class="bg-indigo-900 bg-opacity-50 hover:bg-opacity-80 text-white py-3 px-8 rounded-lg font-semibold shadow-lg transition transform hover:scale-105 border border-indigo-400"
              >
                Daftar Gratis
              </Link>
            </div>
            <div class="mt-16 max-w-5xl w-full">
              <div class="relative bg-gray-900 bg-opacity-30 rounded-xl shadow-2xl backdrop-blur-sm border border-white border-opacity-10 p-2">
                <div class="h-8 flex items-center px-4">
                  <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                  </div>
                </div>
                <!-- <div class="bg-gradient-to-br from-gray-900 to-indigo-900 rounded-lg overflow-hidden">
                  <img 
                    src="/api/placeholder/1200/600" 
                    alt="Dashboard Preview" 
                    class="w-full h-auto rounded-lg shadow-inner transform hover:scale-105 transition-transform duration-700 ease-in-out"
                  />
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Fitur Utama</h2>
            <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">Semua yang Anda butuhkan untuk rekrutmen modern</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
              Platform terintegrasi untuk mengelola seluruh proses rekrutmen dari awal hingga akhir
            </p>
          </div>

          <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
              <div v-for="feature in features" :key="feature.title" class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-16 w-16 bg-indigo-500 bg-opacity-10 rounded-bl-full transition-all duration-300 group-hover:bg-opacity-20"></div>
                <div class="text-indigo-600 mb-4">
                  <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                  </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">{{ feature.title }}</h3>
                <p class="text-gray-500">{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonials Section -->
      <div class="bg-indigo-800 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h2 class="text-base font-semibold text-indigo-300 tracking-wide uppercase">Testimonial</h2>
            <p class="mt-2 text-3xl font-extrabold text-white sm:text-4xl">Apa Kata Klien Kami</p>
          </div>

          <div class="mt-16 grid gap-6 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
            <div v-for="testimonial in testimonials" :key="testimonial.name" class="bg-white bg-opacity-10 backdrop-blur-lg p-6 rounded-lg shadow-lg border border-indigo-500 border-opacity-20 transition-transform hover:transform hover:scale-105 duration-300">
              <div class="flex-shrink-0 mb-4">
                <svg class="h-6 w-6 text-indigo-300" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                </svg>
              </div>
              <p class="text-white mb-4">{{ testimonial.content }}</p>
              <div class="border-t border-indigo-500 border-opacity-20 pt-4">
                <p class="text-indigo-200 font-medium">{{ testimonial.name }}</p>
                <p class="text-indigo-300 text-sm">{{ testimonial.position }}, {{ testimonial.company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-12 sm:px-12 sm:py-16 lg:flex lg:items-center lg:justify-between">
              <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                  <span class="block">Siap meningkatkan rekrutmen?</span>
                  <span class="block text-indigo-200">Mulai gunakan sistem kami sekarang.</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-indigo-100">
                  Daftar gratis dan nikmati 30 hari uji coba tanpa batasan fitur.
                </p>
              </div>
              <div class="mt-8 lg:mt-0 lg:ml-8">
                <div class="flex flex-col sm:flex-row gap-4">
                  <Link
                    href="/register"
                    class="bg-white px-8 py-3 border border-transparent rounded-lg shadow text-base font-medium text-indigo-700 hover:bg-indigo-50 transform hover:scale-105 transition"
                  >
                    Mulai Sekarang
                  </Link>
                  <Link
                    href="/demo"
                    class="bg-indigo-900 bg-opacity-50 px-8 py-3 border border-indigo-300 border-opacity-40 rounded-lg shadow text-base font-medium text-white hover:bg-opacity-70 transform hover:scale-105 transition"
                  >
                    Lihat Demo
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </DefaultLayout>
</template>

<style scoped>
@keyframes float {
  0% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(-20px) translateX(10px);
  }
  100% {
    transform: translateY(0) translateX(0);
  }
}

@keyframes float-delay {
  0% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(-15px) translateX(-15px);
  }
  100% {
    transform: translateY(0) translateX(0);
  }
}

@keyframes float-slow {
  0% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(15px) translateX(10px);
  }
  100% {
    transform: translateY(0) translateX(0);
  }
}

.animate-float {
  animation: float 8s ease-in-out infinite;
}

.animate-float-delay {
  animation: float-delay 10s ease-in-out infinite;
}

.animate-float-slow {
  animation: float-slow 12s ease-in-out infinite;
}
</style>