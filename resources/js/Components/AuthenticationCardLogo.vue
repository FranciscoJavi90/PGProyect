<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="shrink-0 flex items-center">
        <Link :href="route('dashboard')">
            <img src="img/logo.jpg" alt="Logo" class="block h-25 w-auto" />
        </Link>
    </div>
</template>