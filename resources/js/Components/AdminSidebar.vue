<script setup>
import { ref } from 'vue'
import { HomeIcon, UsersIcon, BriefcaseIcon, ClipboardDocumentListIcon, PlusIcon, PencilIcon, ArrowDownTrayIcon, ArrowUpTrayIcon, } from '@heroicons/vue/24/outline'

const menu = ref([
  {
    category: 'General',
    items: [
      { name: 'Dashboard', icon: HomeIcon, link: '/admin/dashboard' },
      { 
        name: 'Users', 
        icon: UsersIcon, 
        link: '/admin/user',
        submenu: [
          { name: 'Manage Users', link: '/admin/user', icon: PencilIcon },
          { name: 'Add User', link: '/admin/user/create', icon: PlusIcon },
        ],
      },
    ],
  },
  {
    category: 'HR',
    items: [
      { 
        name: 'Jobs', 
        icon: BriefcaseIcon, 
        link: '/admin/jobs',
        submenu: [
          { name: 'Manage Jobs', link: '/admin/jobs', icon: PencilIcon },
          { name: 'Add Job', link: '/admin/jobs/create', icon: PlusIcon },
        ],
      },
      { 
        name: 'Applications', 
        icon: ClipboardDocumentListIcon, 
        link: '/applications',
        submenu: [
          { name: 'Manage Applicant', link: '/admin/applicants', icon: PencilIcon }
        ] 
      },
          {
      name: 'Data Management',
      icon: ClipboardDocumentListIcon,
      link: '#',
      submenu: [
        { name: 'Import Data', link: '/admin/import', icon: ArrowUpTrayIcon },
        { name: 'Export Data', link: '/admin/export', icon: ArrowDownTrayIcon },
      ]
    }
    ],
  },
])

const openDropdown = ref(null)

function toggleDropdown(name) {
  openDropdown.value = openDropdown.value === name ? null : name
}
</script>

<template>
  <nav class="w-64 bg-gray-800 min-h-screen text-white p-4">
    <div v-for="section in menu" :key="section.category" class="mb-6">
      <h3 class="uppercase text-xs font-semibold mb-2 text-gray-400">{{ section.category }}</h3>
      <ul>
        <li v-for="item in section.items" :key="item.name" class="mb-1">
          <div>
            <a
              href="#"
              class="flex items-center justify-between px-3 py-2 rounded hover:bg-gray-700 cursor-pointer"
              @click.prevent="item.submenu ? toggleDropdown(item.name) : null"
            >
              <div class="flex items-center space-x-3">
                <component :is="item.icon" class="h-5 w-5" />
                <span>{{ item.name }}</span>
              </div>
              <svg
                v-if="item.submenu"
                :class="{'transform rotate-90': openDropdown === item.name}"
                class="h-4 w-4 transition-transform duration-200"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- Submenu -->
            <ul
              v-if="item.submenu"
              v-show="openDropdown === item.name"
              class="ml-8 mt-1 space-y-1 text-sm"
            >
              <li v-for="sub in item.submenu" :key="sub.name">
                <a
                  :href="sub.link"
                  class="flex items-center space-x-2 px-3 py-1 rounded hover:bg-gray-700"
                >
                  <component v-if="sub.icon" :is="sub.icon" class="h-4 w-4" />
                  <span>{{ sub.name }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>
