<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-4 border-b">
        <h2 class="text-xl font-bold">Admin Dashboard</h2>
      </div>
      <nav class="flex-1 px-4 py-6 space-y-2">
        <Link
          :href="route('dashboard')"
          class="block px-3 py-2 rounded hover:bg-gray-200"
          :class="isActive(route('dashboard')) ? 'bg-blue-500 text-white' : ''"
        >
          Dashboard
        </Link>
        <Link
          :href="route('users.index')"
          class="block px-3 py-2 rounded hover:bg-gray-200"
          :class="isActive(route('users.index')) ? 'bg-blue-500 text-white' : ''"
        >
          Users
        </Link>
        <Link
          :href="route('jobs.index')"
          class="block px-3 py-2 rounded hover:bg-gray-200"
          :class="isActive(route('jobs.index')) ? 'bg-blue-500 text-white' : ''"
        >
          Jobs
        </Link>
        <Link
          :href="route('applications.index')"
          class="block px-3 py-2 rounded hover:bg-gray-200"
          :class="isActive(route('applications.index')) ? 'bg-blue-500 text-white' : ''"
        >
          Applications
        </Link>
      </nav>
      <div class="p-4 border-t">
        <p class="text-sm text-gray-600">Logged in as:</p>
        <p class="font-semibold">{{ user.name }} ({{ user.role }})</p>
      </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-bold mb-6">Dashboard Overview</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded p-6">
          <h2 class="text-lg font-semibold text-gray-700 mb-2">Users</h2>
          <p class="text-4xl font-bold text-blue-600">{{ stats.users }}</p>
        </div>
        <div class="bg-white shadow rounded p-6">
          <h2 class="text-lg font-semibold text-gray-700 mb-2">Jobs</h2>
          <p class="text-4xl font-bold text-green-600">{{ stats.jobs }}</p>
        </div>
        <div class="bg-white shadow rounded p-6">
          <h2 class="text-lg font-semibold text-gray-700 mb-2">Applications</h2>
          <p class="text-4xl font-bold text-purple-600">{{ stats.applications }}</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const { props } = usePage();

const user = props.value.user;
const stats = props.value.stats;

function isActive(url) {
  return window.location.pathname === new URL(url, window.location.origin).pathname;
}
</script>

<style scoped>
main::-webkit-scrollbar {
  width: 8px;
}
main::-webkit-scrollbar-thumb {
  background-color: rgba(100, 116, 139, 0.5);
  border-radius: 4px;
}
</style>
