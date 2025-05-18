<template>
  <div class="bg-white p-6 rounded-xl shadow h-[300px]">
    <h2 class="text-lg font-semibold mb-4">Statistik Data</h2>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

// Registrasi chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  stats: Object
})

const chartData = {
  labels: ['Users', 'Jobs', 'Applications'],
  datasets: [
    {
      label: 'Total',
      backgroundColor: ['#6366f1', '#22c55e', '#eab308'],
      data: [props.stats.users, props.stats.jobs, props.stats.applications]
    }
  ]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    }
  }
}
</script>
