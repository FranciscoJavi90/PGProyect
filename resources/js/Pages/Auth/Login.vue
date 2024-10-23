<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const isEmailFocused = ref(false);
const isPasswordFocused = ref(false);

const animateBackground = () => {
    const bg = document.querySelector('.animated-bg');
    let x = 0;
    let y = 0;

    const moveBackground = () => {
        x += 0.5;
        y += 0.5;
        bg.style.backgroundPosition = `${x}px ${y}px`;
        requestAnimationFrame(moveBackground);
    };

    moveBackground();
};

onMounted(() => {
    animateBackground();
});
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex items-center justify-center animated-bg py-12 px-4 sm:px-6 lg:px-8">
        <AuthenticationCard class="max-w-md w-full space-y-8 bg-white bg-opacity-90 backdrop-filter backdrop-blur-lg p-8 rounded-xl shadow-2xl transform hover:scale-105 transition-all duration-300">
            <template #logo>
                <AuthenticationCardLogo class="mx-auto h-16 w-auto animate-pulse" />
            </template>

            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 animate-fade-in-down">
                Iniciar Sesión
            </h2>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md animate-fade-in">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="relative">
                        <InputLabel for="email" :value="isEmailFocused ? 'Correo Electrónico' : ''" class="absolute left-2 top-2 text-sm text-gray-500 transition-all duration-300" :class="{ 'opacity-0': !isEmailFocused }" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                            :class="{ 'pt-6': isEmailFocused }"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Correo Electrónico"
                            @focus="isEmailFocused = true"
                            @blur="isEmailFocused = form.email !== ''"
                        />
                    </div>
                    <div class="relative">
                        <InputLabel for="password" :value="isPasswordFocused ? 'Contraseña' : ''" class="absolute left-2 top-2 text-sm text-gray-500 transition-all duration-300" :class="{ 'opacity-0': !isPasswordFocused }" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-all duration-300"
                            :class="{ 'pt-6': isPasswordFocused }"
                            required
                            autocomplete="current-password"
                            placeholder="Contraseña"
                            @focus="isPasswordFocused = true"
                            @blur="isPasswordFocused = form.password !== ''"
                        />
                        <button 
                            type="button" 
                            @click="togglePassword" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <InputError class="mt-2 animate-fade-in" :message="form.errors.email" />
                <InputError class="mt-2 animate-fade-in" :message="form.errors.password" />

                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex items-center">
                        <Checkbox v-model:checked="form.remember" id="remember-me" name="remember-me" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Recuérdame
                        </label>
                    </div>

                    <div class="text-sm mt-2 sm:mt-0">
                        <Link v-if="canResetPassword" :href="route('password.request')" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-300">
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>
                </div>

                <div>
                    <PrimaryButton type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300" :class="{ 'opacity-75 cursor-not-allowed': form.processing }" :disabled="form.processing">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Iniciar Sesión
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </div>
</template>

<style scoped>
.animated-bg {
    background: linear-gradient(45deg, #f3f4f6, #e5e7eb, #d1d5db, #e5e7eb);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-fade-in-down {
    animation: fadeInDown 0.5s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.input-group {
    transition: all 0.3s ease;
}

.input-group:focus-within {
    transform: scale(1.02);
}
</style>