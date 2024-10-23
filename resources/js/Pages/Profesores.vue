<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { IonIcon } from '@ionic/vue';
import { personOutline, schoolOutline, bookOutline, idCardOutline } from 'ionicons/icons';

// Importar Bootstrap como una variable
import * as bootstrap from 'bootstrap';

const teachers = ref([]);
const form = useForm({
    id: '',
    nombre_completo: '',
    titulo_profesional: '',
    especialidad: '',
    numero_empleado: ''
});
const teacherToDelete = ref(null);
const isLoading = ref(true);
const isSubmitting = ref(false);
const updatedTeacherId = ref(null);

const obtenerProfesores = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/teachers');
        teachers.value = response.data.teachers;
    } catch (error) {
        console.error('Error al obtener los profesores:', error);
    } finally {
        isLoading.value = false;
    }
};

// Inicializar los datos cuando se monta el componente
onMounted(() => {
    obtenerProfesores();
});

const submit = async () => {
    isSubmitting.value = true;
    try {
        if (form.id) {
            await axios.put(`/teachers/${form.id}`, form);
            updatedTeacherId.value = form.id;
        } else {
            const response = await axios.post('/teachers', form);
            updatedTeacherId.value = response.data.id;
        }
        await obtenerProfesores();
        form.reset();
        await nextTick();
        updatedTeacherId.value = null;
    } catch (error) {
        console.error('Error al guardar el profesor:', error);
    } finally {
        isSubmitting.value = false;
    }
};

const editTeacher = (teacher) => {
    form.id = teacher.id;
    form.nombre_completo = teacher.nombre_completo;
    form.titulo_profesional = teacher.titulo_profesional;
    form.especialidad = teacher.especialidad;
    form.numero_empleado = teacher.numero_empleado;
};

const confirmDeleteTeacher = (teacher) => {
    teacherToDelete.value = teacher;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
};

const deleteTeacher = async () => {
    try {
        await axios.delete(`/teachers/${teacherToDelete.value.id}`);
        await obtenerProfesores();
        teacherToDelete.value = null;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    } catch (error) {
        console.error('Error al eliminar el profesor:', error);
    }
};

const limpiarCampos = () => {
    form.reset();
};
</script>

<template>
    <AppLayout title="Profesores">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profesores
            </h2>
        </template>

        <div class="container mt-4">
            <form @submit.prevent="submit" class="mb-4 p-4 bg-white shadow-sm rounded">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <IonIcon :icon="personOutline" />
                            </span>
                            <input v-model="form.nombre_completo" class="form-control" placeholder="Nombre Completo" required />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <IonIcon :icon="schoolOutline" />
                            </span>
                            <input v-model="form.titulo_profesional" class="form-control" placeholder="Título Profesional" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <IonIcon :icon="bookOutline" />
                            </span>
                            <input v-model="form.especialidad" class="form-control" placeholder="Especialidad" required />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <IonIcon :icon="idCardOutline" />
                            </span>
                            <input v-model="form.numero_empleado" class="form-control" placeholder="Número Empleado" required />
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2" :disabled="isSubmitting">
                        <i class="fas fa-save me-2"></i>
                        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
                    </button>
                    <button type="button" class="btn btn-secondary" @click="limpiarCampos">
                        <i class="fas fa-broom me-2"></i>
                        Limpiar
                    </button>
                </div>
            </form>

            <div class="bg-white shadow-sm rounded p-4">
                <h3 class="mb-4">Lista de Profesores</h3>
                <div v-if="isLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <transition-group name="fade" tag="table" class="table table-hover" v-else>
                    <thead class="table-light" key="head">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Título Profesional</th>
                            <th>Especialidad</th>
                            <th>Número Empleado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="teacher in teachers" :key="teacher.id" :class="{ 'highlight': teacher.id === updatedTeacherId }">
                            <td>{{ teacher.id }}</td>
                            <td>{{ teacher.nombre_completo }}</td>
                            <td>{{ teacher.titulo_profesional }}</td>
                            <td>{{ teacher.especialidad }}</td>
                            <td>{{ teacher.numero_empleado }}</td>
                            <td>
                                <button class="btn btn-outline-warning btn-sm me-2" @click="editTeacher(teacher)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" @click="confirmDeleteTeacher(teacher)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </transition-group>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar al profesor <strong>{{ teacherToDelete?.nombre_completo }}</strong>?</p>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="deleteTeacher">
                            <i class="fas fa-trash-alt me-2"></i>
                            Eliminar
                        </button>
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

.highlight {
    animation: highlight 2s ease-in-out;
}

@keyframes highlight {
    0% {
        background-color: #fff3cd;
    }
    50% {
        background-color: #fff3cd;
    }
    100% {
        background-color: transparent;
    }
}

.table {
    transition: all 0.3s ease-in-out;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transition: background-color 0.3s ease-in-out;
}

.btn-outline-warning,
.btn-outline-danger {
    transition: all 0.3s ease-in-out;
}

.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transition: box-shadow 0.3s ease-in-out;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control {
    border-left: none;
}

.input-group-text ion-icon {
    font-size: 1.2rem;
}
</style>