<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import { ChartPieIcon, UserGroupIcon, BookOpenIcon, ClockIcon, UserIcon } from '@heroicons/vue/24/solid';
import axios from 'axios';

const page = usePage();
const userName = ref('');
const stats = ref({
  studentsCount: 0,
  averageGrade: 0,
  coursesCount: 0,
  teachingHours: 0
});
const userRol = computed(() => page.props.auth.user.rol);
const greeting = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Buenos días ☀️';
  if (hour < 18) return 'Buenas tardes 🏙️';
  return 'Buenas noches 🌕';
});

const loadUserData = async () => {
  try {
    const response = await axios.get('/authenticated-user');
    userName.value = response.data.name;
  } catch (error) {
    console.error('Error al obtener el usuario autenticado:', error);
  }
};

const loadStats = async () => {
  // Simulating API call for stats
  await new Promise(resolve => setTimeout(resolve, 1000));
  stats.value = {
    studentsCount: 120,
    averageGrade: 8.5,
    coursesCount: 5,
    teachingHours: 25
  };
};

onMounted(() => {
  loadUserData();
  loadStats();
});
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Dashboard Profesor
        </h2>
        <NavLink v-if="userRol === 'Profesor'" :href="route('usuarios')" :active="route().current('usuarios')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-300 flex items-center">
          <UserIcon class="w-5 h-5 mr-2" />
          Manejo Usuarios
        </NavLink>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:p-8">
            <transition name="fade" appear>
              <h1 class="text-4xl font-bold text-gray-800 mb-6">
                {{ greeting }}, <span class="text-indigo-600">{{ userName }}</span>
              </h1>
            </transition>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
              <transition-group name="fade" appear>
                <div v-for="(stat, index) in [
                  { name: 'Estudiantes', value: stats.studentsCount, icon: UserGroupIcon, color: 'bg-blue-500' },
                  { name: 'Promedio de Calificaciones', value: stats.averageGrade, icon: ChartPieIcon, color: 'bg-green-500' },
                  { name: 'Cursos Activos', value: stats.coursesCount, icon: BookOpenIcon, color: 'bg-yellow-500' },
                  { name: 'Horas de Enseñanza', value: stats.teachingHours, icon: ClockIcon, color: 'bg-red-500' }
                ]" :key="index"
                  class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                  <div class="flex items-center">
                    <div :class="[stat.color, 'p-3 rounded-full']">
                      <component :is="stat.icon" class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                      <h2 class="text-lg font-semibold text-gray-700">{{ stat.name }}</h2>
                      <p class="text-3xl font-bold text-gray-800">{{ stat.value }}</p>
                    </div>
                  </div>
                </div>
              </transition-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <transition name="slide-fade" appear>
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg p-6 text-white">
                  <h3 class="text-2xl font-semibold mb-4">Consejo del Día</h3>
                  <p class="text-lg">
                    "La educación es el arma más poderosa que puedes usar para cambiar el mundo." - Nelson Mandela
                  </p>
                </div>
              </transition>

              <transition name="slide-fade" appear>
                <div class="bg-white rounded-lg shadow-md p-6">
                  <h3 class="text-2xl font-semibold text-gray-800 mb-4">Próximas Actividades</h3>
                  <ul class="space-y-2">
                    <li v-for="(activity, index) in [
                      'Reunión de profesores - 10:00 AM',
                      'Entrega de calificaciones - 2:00 PM',
                      'Tutoría con estudiantes - 4:00 PM'
                    ]" :key="index" class="flex items-center">
                      <span class="w-4 h-4 bg-indigo-600 rounded-full mr-2"></span>
                      {{ activity }}
                    </li>
                  </ul>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(20px);
  opacity: 0;
}
</style>