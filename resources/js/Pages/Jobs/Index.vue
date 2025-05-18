<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({ jobs: Array, userApplications: Array });
const filteredJobs = ref([]);
const searchQuery = ref('');
const selectedLocation = ref('Semua Lokasi');
const selectedStatus = ref('Semua Status');

// List lokasi dan tipe unik untuk filter
const locations = ref([]);
const jobStatuses = ref([]);

// Animasi saat halaman dimuat
const isLoaded = ref(false);

onMounted(() => {
  // Set data awal
  filteredJobs.value = props.jobs;
  
  // Ekstrak lokasi dan tipe unik untuk filter
  if (props.jobs && props.jobs.length > 0) {
    const uniqueLocations = new Set(props.jobs.map(job => job.location));
    locations.value = ['Semua Lokasi', ...uniqueLocations];
    
    const uniqueStatuses = new Set(props.jobs.map(job => job.is_active ? 'Open' : 'Close'));
    jobStatuses.value = ['Semua Status', ...uniqueStatuses];
  }
  
  // Aktifkan animasi loading
  setTimeout(() => {
    isLoaded.value = true;
  }, 100);
});

// Filter berdasarkan pencarian dan dropdown
function filterJobs() {
  filteredJobs.value = props.jobs.filter(job => {
    const matchesSearch = searchQuery.value === '' || 
      job.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      job.description.toLowerCase().includes(searchQuery.value.toLowerCase());
      
    const matchesLocation = selectedLocation.value === 'Semua Lokasi' || 
      job.location === selectedLocation.value;
      
    const matchesStatus = selectedStatus.value === 'Semua Status' || 
        (job.is_active ? 'Open' : 'Close') === selectedStatus.value;

    return matchesSearch && matchesLocation && matchesStatus;
  });
}

// Format tanggal
function formatDate(dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  }).format(date);
}
</script>

<template>
  <DefaultLayout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section dengan Background -->
      <div class="relative overflow-hidden rounded-xl mb-10">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-800 py-12 px-8 rounded-xl">
          <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Lowongan Pekerjaan</h1>
            <p class="text-blue-100 text-lg md:text-xl max-w-2xl">
              Temukan karir impian Anda dan bergabunglah dengan tim kami yang dinamis dan berdedikasi.
            </p>
          </div>
          <div class="absolute right-0 bottom-0 opacity-10">
            <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 6H16V4C16 2.89 15.11 2 14 2H10C8.89 2 8 2.89 8 4V6H4C2.89 6 2 6.89 2 8V19C2 20.11 2.89 21 4 21H20C21.11 21 22 20.11 22 19V8C22 6.89 21.11 6 20 6ZM10 4H14V6H10V4ZM20 19H4V8H20V19Z" fill="white"/>
            </svg>
          </div>
        </div>
      </div>
      
      <!-- Filter dan Search Section -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
          <div class="flex-1">
            <div class="relative">
              <input 
                type="text" 
                v-model="searchQuery" 
                @input="filterJobs"
                placeholder="Cari posisi atau kata kunci" 
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <span class="absolute left-3 top-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </span>
            </div>
          </div>
          
          <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <select 
              v-model="selectedLocation" 
              @change="filterJobs"
              class="rounded-lg border border-gray-300 py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option v-for="location in locations" :key="location">{{ location }}</option>
            </select>
            
            <select 
  v-model="selectedStatus" 
  @change="filterJobs"
  class="rounded-lg border border-gray-300 py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
>
  <option v-for="status in jobStatuses" :key="status">{{ status }}</option>
</select>
          </div>
        </div>
      </div>
      
      <!-- Job Listings -->
      <div v-if="filteredJobs.length === 0" 
           class="bg-white rounded-xl shadow-md p-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-blue-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-xl font-medium text-gray-500 mb-2">Tidak ada lowongan pekerjaan</h3>
        <p class="text-gray-400">Belum ada posisi yang tersedia saat ini atau tidak ditemukan berdasarkan filter yang dipilih.</p>
      </div>
      
      <div 
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        :class="{'opacity-100 transform translate-y-0': isLoaded, 'opacity-0 transform translate-y-4': !isLoaded}"
        style="transition: all 0.5s ease-in-out;"
      >
        <div
          v-for="(job, index) in filteredJobs"
          :key="job.id"
          class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
          :style="`transition-delay: ${index * 50}ms`"
        >
          <div class="p-6">
            <div class="flex items-center mb-4">
              <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </span>
              <h3 class="text-xl font-bold text-blue-800 line-clamp-1">{{ job.title }}</h3>
            </div>
            
            <p class="text-gray-600 mb-4 line-clamp-3">{{ job.description }}</p>
            
            <div class="flex flex-wrap gap-2 mb-4">
              <span class="bg-green-50 text-green-700 rounded-full px-3 py-1 text-sm font-medium" v-if="job.is_active">
  Open
</span>
<span class="bg-red-50 text-red-700 rounded-full px-3 py-1 text-sm font-medium" v-else>
  Close
</span>
              <span class="bg-indigo-50 text-indigo-700 rounded-full px-3 py-1 text-sm font-medium">
                {{ job.location }}
              </span>
              <span v-if="job.postedDate" class="bg-gray-50 text-gray-700 rounded-full px-3 py-1 text-sm font-medium">
                {{ formatDate(job.postedDate) }}
              </span>
            </div>
            
            <div class="flex justify-end">
              <button
                    @click="$inertia.get(route('jobs.show', job.id))"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium text-sm">
                    Lihat Detail
                </button>

            </div> 
          </div>
        </div>
      </div>
      
      <!-- Pagination (Optional) -->
      <div v-if="filteredJobs.length > 9" class="mt-10 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button class="px-3 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">Prev</button>
          <button class="px-3 py-1 rounded bg-blue-600 text-white">1</button>
          <button class="px-3 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">2</button>
          <button class="px-3 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">3</button>
          <button class="px-3 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">Next</button>
        </nav>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>