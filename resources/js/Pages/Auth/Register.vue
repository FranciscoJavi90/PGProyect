<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
    rol: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const passwordStrength = ref(0);

const updatePasswordStrength = (password) => {
    const strength = password.length >= 8 ? 1 : 0
        + /[A-Z]/.test(password) ? 1 : 0
        + /[a-z]/.test(password) ? 1 : 0
        + /[0-9]/.test(password) ? 1 : 0
        + /[^A-Za-z0-9]/.test(password) ? 1 : 0;
    passwordStrength.value = Math.min(strength, 4);
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-r from-blue-500 to-purple-600">
        <AuthenticationCard class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg transform hover:scale-105 transition-all duration-300">
            <template #logo>
                <AuthenticationCardLogo class="w-20 h-20 fill-current text-gray-500" />
            </template>

            <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900">
                Crear una cuenta
            </h2>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <InputLabel for="name" value="Nombre" class="sr-only" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Nombre"
                        />
                    </div>
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" class="sr-only" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="username"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Correo Electrónico"
                        />
                    </div>
                    <div>
                        <InputLabel for="password" value="Contraseña" class="sr-only" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Contraseña"
                            @input="updatePasswordStrength($event.target.value)"
                        />
                    </div>
                    <div>
                        <InputLabel for="password_confirmation" value="Confirmar Contraseña" class="sr-only" />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Confirmar Contraseña"
                        />
                    </div>
                </div>

                <div class="mt-4">
                    <InputLabel for="rol" value="Rol" class="sr-only" />
                    <select
                        id="rol"
                        v-model="form.rol"
                        required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                        <option value="" disabled>Seleccione un rol</option>
                        <option value="Directora">Directora</option>
                        <option value="Secretaria">Secretaria</option>
                        <option value="Profesor">Profesor</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            Acepto los <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Términos y Condiciones</a>
                        </label>
                    </div>
                </div>

                <div>
                    <PrimaryButton type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" :class="{ 'opacity-75 cursor-not-allowed': form.processing }" :disabled="form.processing">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Registrarse
                    </PrimaryButton>
                </div>
            </form>

            <div class="mt-4 text-center">
                <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500">
                    ¿Ya tienes una cuenta? Inicia sesión
                </Link>
            </div>

            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700">Fortaleza de la contraseña:</p>
                <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-in-out" :style="{ width: `${passwordStrength * 25}%` }"></div>
                </div>
            </div>
        </AuthenticationCard>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.authentication-card {
    animation: fadeIn 0.5s ease-out;
}

.input-group {
    transition: all 0.3s ease;
}

.input-group:focus-within {
    transform: scale(1.02);
}
</style>