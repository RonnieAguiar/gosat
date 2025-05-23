<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
} from 'chart.js';

// Registrar elementos do chart
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

interface ChartData {
  labels: string[];
  valores: number[];
}

defineProps<{
  consultasPorDia: ChartData;
}>();

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    x: {
      ticks: { color: '#1b1b18' }
    },
    y: {
      beginAtZero: true,
      ticks: { color: '#1b1b18' }
    }
  },
  plugins: {
    legend: {
      labels: {
        color: '#1b1b18'
      }
    }
  }
};
</script>

<template>
  <Head title="Dashboard" />

  <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <header class="mb-6 w-full max-w-4xl text-sm">
      <nav class="flex justify-end gap-4">
        <Link :href="route('home')" class="px-5 py-1.5 text-[#1b1b18]">Consultas</Link>
        <Link :href="route('graficos')" class="px-5 py-1.5 text-[#1b1b18]">Gráficos</Link>
      </nav>
    </header>

    <main class="w-full max-w-4xl bg-white p-6 rounded-xl shadow-md space-y-6 dark:bg-[#1b1b18]">
      <h1 class="text-xl font-semibold">Dashboard de Consultas</h1>

      <div class="grid grid-cols-1">
        <div class="bg-white p-4 rounded-xl shadow dark:bg-[#1b1b18]" style="height: 400px;">
          <h2 class="text-lg font-semibold mb-2">Consultas por Dia</h2>
          <Line
            :data="{
              labels: consultasPorDia.labels,
              datasets: [
                {
                  label: 'Consultas',
                  data: consultasPorDia.valores,
                  fill: false,
                  borderColor: '#3B82F6',
                  tension: 0.1
                }
              ]
            }"
            :options="chartOptions"
          />
        </div>
      </div>
    </main>
  </div>
</template>
