<script setup>
import { ref, onMounted, computed, markRaw } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BarChart, Download, Users, GraduationCap, BookOpen } from 'lucide-vue-next';

// Datos de ejemplo para las estadísticas
const stats = ref([
  { name: 'Total de Profesores', value: '127', change: '+5%', changeType: 'increase', icon: Users },
  { name: 'Total de Alumnos', value: '1,543', change: '+12%', changeType: 'increase', icon: GraduationCap },
  { name: 'Cursos Activos', value: '42', change: '+3', changeType: 'increase', icon: BookOpen },
]);

const isLoaded = ref(false);
const chartLoaded = ref(false);
const BarChartComponent = ref(null);

const chartData = {
  labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
  datasets: [
    {
      label: 'Nuevos Alumnos',
      backgroundColor: '#8b5cf6',
      data: [40, 55, 75, 81, 56, 72],
    },
    {
      label: 'Cursos Iniciados',
      backgroundColor: '#10b981',
      data: [28, 32, 40, 49, 36, 45],
    },
  ],
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
};

onMounted(async () => {
  setTimeout(() => {
    isLoaded.value = true;
  }, 100);

  // Importación dinámica de Chart.js y vue-chartjs
  const { Chart, registerables } = await import('chart.js');
  Chart.register(...registerables);
  const { Bar } = await import('vue-chartjs');
  BarChartComponent.value = markRaw(Bar);
  chartLoaded.value = true;
});

const exportToExcel = () => {
  // Aquí iría la lógica para exportar a Excel
  console.log('Exportando a Excel...');
};

const animationClass = computed(() => ({
  'translate-y-0 opacity-100': isLoaded.value,
  'translate-y-4 opacity-0': !isLoaded.value,
}));
</script>

<template>
    <AppLayout title="Reportes Educativos">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Reportes Educativos
        </h2>
      </template>
  
      <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
              <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Reportes</h1>
                <button @click="exportToExcel" class="bg-violet-500 hover:bg-violet-600 text-white font-bold py-2 px-4 rounded-full inline-flex items-center transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-opacity-50">
                  <Download class="w-5 h-5 mr-2" />
                  Exportar a Excel
                </button>
              </div>
  
              <div class="mt-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                  <div v-for="item in stats" :key="item.name" :class="['transition-all duration-500 ease-in-out transform', animationClass]">
                    <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                      <dt>
                        <div class="absolute bg-violet-500 rounded-md p-3">
                          <component :is="item.icon" class="h-6 w-6 text-white" aria-hidden="true" />
                        </div>
                        <p class="ml-16 text-sm font-medium text-gray-500 truncate">{{ item.name }}</p>
                      </dt>
                      <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                        <p class="text-2xl font-semibold text-gray-900">{{ item.value }}</p>
                        <p :class="[item.changeType === 'increase' ? 'text-green-600' : 'text-red-600', 'ml-2 flex items-baseline text-sm font-semibold']">
                          {{ item.change }}
                        </p>
                      </dd>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="mt-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Tendencias Mensuales</h2>
                <div class="bg-white p-6 rounded-lg shadow-md" :class="['transition-all duration-500 ease-in-out transform', animationClass]">
                  <component v-if="chartLoaded" :is="BarChartComponent" :data="chartData" :options="chartOptions" class="h-64" />
                  <div v-else class="h-64 flex items-center justify-center">
                    <p class="text-gray-500">Cargando gráfico...</p>
                  </div>
                </div>
              </div>
  
              <div class="mt-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Cursos Más Populares</h2>
                <div class="bg-white overflow-hidden shadow-md rounded-lg" :class="['transition-all duration-500 ease-in-out transform', animationClass]">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Curso</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profesor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumnos Inscritos</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificación Promedio</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="course in courses" :key="course.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ course.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ course.professor }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ course.students }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ course.rating }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>