<template>
  <div :class="`p-6 rounded-xl shadow text-white ${color}`">
    <div class="flex items-center space-x-4">
      <div>
        <component :is="iconComponent" class="w-10 h-10" />
      </div>
      <div>
        <p class="text-sm">{{ title }}</p>
        <p class="text-2xl font-bold">{{ value }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { Users, Briefcase, ClipboardList } from 'lucide-vue-next'

const props = defineProps({
  title: String,
  value: Number,
  icon: String,
  color: String
})

const iconComponent = computed(() => {
  switch (props.icon) {
    case 'users':
      return Users
    case 'briefcase':
      return Briefcase
    case 'clipboard-list':
      return ClipboardList
    default:
      return Users
  }
})
</script>
