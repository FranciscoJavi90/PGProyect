<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Register from '@/Pages/Auth/Register.vue'; // Asegúrate de que la ruta sea correcta
</script>

<template>
    <AppLayout title="Cursos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cursos
            </h2>
        </template>
        <div>
            <Register />
        </div>
    </AppLayout>
</template>