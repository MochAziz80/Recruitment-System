<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from '@/Components/AdminSidebar.vue'
import DashboardCard from '@/Components/DashboardCard.vue'
import StatsChart from '@/Components/StatsChart.vue'
import { 
  ArrowUpIcon, 
  ArrowDownIcon, 
  Bell, 
  Search, 
  User
} from 'lucide-vue-next'

const { props } = usePage()
const stats = props.stats
const showNotifications = ref(false)
const searchQuery = ref('')
const isLoading = ref(true)

// Mock data for recent activity
const recentActivity = [
  { id: 1, type: 'user', message: 'New user registered', time: '5 minutes ago', status: 'success' },
  { id: 2, type: 'job', message: 'New job posted', time: '1 hour ago', status: 'success' },
  { id: 3, type: 'application', message: 'New application submitted', time: '3 hours ago', status: 'pending' },
  { id: 4, type: 'system', message: 'System maintenance completed', time: '1 day ago', status: 'info' }
]

// Mock data for comparison stats
const comparisonStats = {
  users: { 
    change: 12, 
    isIncrease: true 
  },
  jobs: { 
    change: 5, 
    isIncrease: true 
  },
  applications: { 
    change: -3, 
    isIncrease: false 
  }
}

// Notifications
const notifications = [
  { id: 1, message: 'System update scheduled for tomorrow', time: '10 minutes ago', read: false },
  { id: 2, message: '5 new users registered today', time: '1 hour ago', read: false },
  { id: 3, message: 'Weekly report is ready to view', time: '3 hours ago', read: true }
]

// Simulate loading
onMounted(() => {
  setTimeout(() => {
    isLoading.value = false
  }, 1000)
})

// Toggle notifications panel
const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}
</script>

<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Top Navigation Bar -->
      <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <h2 class="text-xl font-bold text-gray-800">JobBoard</h2>
              </div>
            </div>
            
            <div class="flex items-center space-x-4">
              <!-- Search -->
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Search class="h-4 w-4 text-gray-400" />
                </div>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search..."
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
              
              <!-- Notifications -->
              <div class="relative">
                <button @click="toggleNotifications" class="relative p-1 rounded-full text-gray-600 hover:bg-gray-100 focus:outline-none">
                  <Bell class="h-6 w-6" />
                  <span v-if="notifications.filter(n => !n.read).length > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                </button>
                
                <!-- Notifications Panel -->
                <div v-if="showNotifications" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                  <div class="py-2 divide-y divide-gray-100">
                    <div class="px-4 py-2 text-sm font-medium text-gray-700 flex justify-between items-center">
                      <span>Notifications</span>
                      <span class="text-xs text-indigo-600 cursor-pointer">Mark all as read</span>
                    </div>
                    <div v-for="notification in notifications" :key="notification.id" class="px-4 py-3 hover:bg-gray-50">
                      <div class="flex items-start">
                        <div class="flex-shrink-0">
                          <div :class="[notification.read ? 'bg-gray-300' : 'bg-indigo-500', 'h-2 w-2 rounded-full mt-1.5']"></div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                          <p class="text-sm font-medium text-gray-900">{{ notification.message }}</p>
                          <p class="mt-1 text-xs text-gray-500">{{ notification.time }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="px-4 py-2 text-xs text-center text-indigo-600">
                      <a href="#" class="font-medium">View all notifications</a>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Profile dropdown -->
              <div class="relative">
                <button class="rounded-full bg-gray-200 p-1">
                  <User class="h-6 w-6 text-gray-700" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-6">
        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center h-64">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>
        
        <div v-else class="max-w-7xl mx-auto space-y-6">
          <!-- Welcome Message -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
              <p class="mt-1 text-sm text-gray-600">Welcome back! Here's what's happening with your platform today.</p>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <DashboardCard
                  title="Total Users"
                  :value="stats.users"
                  icon="users"
                  color="bg-indigo-500"
                >
                  <template #trend>
                    <div :class="[comparisonStats.users.isIncrease ? 'text-green-600' : 'text-red-600', 'flex items-center text-sm font-medium']">
                      <span v-if="comparisonStats.users.isIncrease">
                        <ArrowUpIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span v-else>
                        <ArrowDownIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span>{{ Math.abs(comparisonStats.users.change) }}%</span>
                      <span class="ml-1 text-gray-500">vs last week</span>
                    </div>
                  </template>
                </DashboardCard>
              </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <DashboardCard
                  title="Total Jobs"
                  :value="stats.jobs"
                  icon="briefcase"
                  color="bg-green-500"
                >
                  <template #trend>
                    <div :class="[comparisonStats.jobs.isIncrease ? 'text-green-600' : 'text-red-600', 'flex items-center text-sm font-medium']">
                      <span v-if="comparisonStats.jobs.isIncrease">
                        <ArrowUpIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span v-else>
                        <ArrowDownIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span>{{ Math.abs(comparisonStats.jobs.change) }}%</span>
                      <span class="ml-1 text-gray-500">vs last week</span>
                    </div>
                  </template>
                </DashboardCard>
              </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <DashboardCard
                  title="Total Applications"
                  :value="stats.applications"
                  icon="clipboard-list"
                  color="bg-yellow-500"
                >
                  <template #trend>
                    <div :class="[comparisonStats.applications.isIncrease ? 'text-green-600' : 'text-red-600', 'flex items-center text-sm font-medium']">
                      <span v-if="comparisonStats.applications.isIncrease">
                        <ArrowUpIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span v-else>
                        <ArrowDownIcon class="h-4 w-4 mr-1" />
                      </span>
                      <span>{{ Math.abs(comparisonStats.applications.change) }}%</span>
                      <span class="ml-1 text-gray-500">vs last week</span>
                    </div>
                  </template>
                </DashboardCard>
              </div>
            </div>
          </div>

          <!-- Charts and Activity Section -->
          <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow rounded-lg lg:col-span-2">
              <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Performance Overview</h3>
                  <div class="flex space-x-3">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Weekly</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Monthly</button>
                    <button class="px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Yearly</button>
                  </div>
                </div>
                <div class="mt-4 h-64">
                  <StatsChart :stats="stats" />
                </div>
              </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Activity</h3>
              </div>
              <div class="border-t border-gray-200">
                <ul role="list" class="divide-y divide-gray-200">
                  <li v-for="activity in recentActivity" :key="activity.id" class="px-4 py-4 sm:px-6">
                    <div class="flex items-center space-x-4">
                      <div :class="[
                        activity.status === 'success' ? 'bg-green-100' : 
                        activity.status === 'pending' ? 'bg-yellow-100' : 'bg-blue-100',
                        'h-8 w-8 rounded-full flex items-center justify-center'
                      ]">
                        <span :class="[
                          activity.status === 'success' ? 'text-green-600' : 
                          activity.status === 'pending' ? 'text-yellow-600' : 'text-blue-600',
                          'text-sm font-medium'
                        ]">
                          {{ activity.type.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          {{ activity.message }}
                        </p>
                        <p class="text-xs text-gray-500">
                          {{ activity.time }}
                        </p>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="py-3 px-6 border-t border-gray-200 text-center">
                  <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all activity</a>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Quick Links -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
              <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <a href="#" class="relative rounded-lg border border-gray-300 bg-white p-4 flex flex-col items-center hover:bg-gray-50">
                  <div class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100 mb-3">
                    <span class="text-indigo-600 text-lg font-medium">+</span>
                  </div>
                  <span class="text-sm font-medium text-gray-900">Add User</span>
                </a>
                <a href="#" class="relative rounded-lg border border-gray-300 bg-white p-4 flex flex-col items-center hover:bg-gray-50">
                  <div class="h-10 w-10 flex items-center justify-center rounded-full bg-green-100 mb-3">
                    <span class="text-green-600 text-lg font-medium">+</span>
                  </div>
                  <span class="text-sm font-medium text-gray-900">Post Job</span>
                </a>
                <a href="#" class="relative rounded-lg border border-gray-300 bg-white p-4 flex flex-col items-center hover:bg-gray-50">
                  <div class="h-10 w-10 flex items-center justify-center rounded-full bg-yellow-100 mb-3">
                    <span class="text-yellow-600 text-lg font-medium">↓</span>
                  </div>
                  <span class="text-sm font-medium text-gray-900">Reports</span>
                </a>
                <a href="#" class="relative rounded-lg border border-gray-300 bg-white p-4 flex flex-col items-center hover:bg-gray-50">
                  <div class="h-10 w-10 flex items-center justify-center rounded-full bg-red-100 mb-3">
                    <span class="text-red-600 text-lg font-medium">⚙️</span>
                  </div>
                  <span class="text-sm font-medium text-gray-900">Settings</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </main>
      
      <!-- Footer -->
      <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
              © 2025 JobBoard Admin. All rights reserved.
            </div>
            <div class="text-sm text-gray-500">
              <span>Version 2.0.1</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<style scoped>
/* Custom styling can be added here if needed */
</style>