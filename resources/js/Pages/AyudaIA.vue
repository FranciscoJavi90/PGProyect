<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import OpenAI from 'openai';

const messages = ref([]);
const newMessage = ref('');
const userName = ref('');
const isLoading = ref(false);

// Inicializa el cliente de OpenAI
const openai = new OpenAI({
  apiKey: import.meta.env.VITE_OPENAI_API_KEY, // Usa la variable de entorno
  dangerouslyAllowBrowser: true // Solo para desarrollo, no uses esto en producción
});

const sendMessage = async () => {
  if (newMessage.value.trim() !== '') {
    const userMessage = { text: newMessage.value, sender: 'user', timestamp: new Date(), userName: userName.value };
    messages.value.push(userMessage);
    newMessage.value = '';
    isLoading.value = true;

    try {
      const response = await openai.chat.completions.create({
        model: "gpt-3.5-turbo",
        messages: [
          { role: "system", content: "Eres un asistente educativo especializado en proporcionar estrategias para mejorar el aprendizaje de los alumnos." },
          ...messages.value.map(msg => ({
            role: msg.sender === 'user' ? 'user' : 'assistant',
            content: msg.text
          }))
        ],
      });

      const aiResponse = response.choices[0].message.content;
      messages.value.push({ text: aiResponse, sender: 'ia', timestamp: new Date() });
    } catch (error) {
      console.error('Error al obtener respuesta de la IA:', error);
      messages.value.push({ text: "Lo siento, hubo un error al procesar tu solicitud.", sender: 'ia', timestamp: new Date() });
    } finally {
      isLoading.value = false;
    }
  }
};

onMounted(async () => {
  try {
    const response = await axios.get('/authenticated-user');
    userName.value = response.data.name;
    messages.value.push({ text: 'Pregúntame lo que necesites, te seré de apoyo con estrategias destinadas a mejorar el aprendizaje de tus alumnos 😊', sender: 'ia', timestamp: new Date() });
  } catch (error) {
    console.error('Error al obtener el usuario autenticado:', error);
  }
});
</script>

<template>
  <AppLayout title="Asistente Personalizado Educativo">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Asistente Personalizado Educativo
      </h2>
    </template>

    <div class="container mx-auto mt-8 px-4">
      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4">
          <h3 class="text-2xl font-bold text-white">Conversación</h3>
        </div>
        <div class="conversation p-4 h-96 overflow-y-auto bg-gray-50">
          <div v-for="(message, index) in messages" :key="index" 
               class="mb-4 flex" 
               :class="{'justify-end': message.sender === 'user', 'justify-start': message.sender === 'ia'}">
            <div :class="['p-3', 'rounded-lg', 'max-w-xs', 'lg:max-w-md', 
                          message.sender === 'user' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 
                          'bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800']">
              <p>{{ message.text }}</p>
              <div class="text-xs mt-1 opacity-75">
                <strong v-if="message.sender === 'user'">{{ message.userName }}</strong>
                {{ message.sender === 'ia' ? 'Asistente Educativo' : '' }} - {{ new Date(message.timestamp).toLocaleString() }}
              </div>
            </div>
          </div>
        </div>
        <div class="p-4 bg-gray-100">
          <form @submit.prevent="sendMessage" class="flex">
            <input v-model="newMessage" type="text" 
                  class="flex-grow px-4 py-2 mr-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600" 
                  placeholder="Escribe un mensaje..." 
                  :disabled="isLoading" />
            <button type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2"
                    :disabled="isLoading">
              {{ isLoading ? 'Enviando...' : 'Enviar' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.conversation {
  scrollbar-width: thin;
  scrollbar-color: rgba(155, 155, 155, 0.5) transparent;
}

.conversation::-webkit-scrollbar {
  width: 6px;
}

.conversation::-webkit-scrollbar-track {
  background: transparent;
}

.conversation::-webkit-scrollbar-thumb {
  background-color: rgba(155, 155, 155, 0.5);
  border-radius: 20px;
  border: transparent;
}
</style>