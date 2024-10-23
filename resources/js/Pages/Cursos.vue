<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Importar Bootstrap como una variable
import * as bootstrap from 'bootstrap';

const courses = ref([]);
const teachers = ref([]);
const form = useForm({
    id: '',
    nombre: '',
    descripcion: '',
    id_profesor: ''
});
const courseToDelete = ref(null);
const currentPage = ref(1);
const totalPages = ref(1);
const isLoading = ref(true);
const isSubmitting = ref(false);
const updatedCourseId = ref(null);

const obtenerCursos = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/course?page=${page}`);
        courses.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error('Error al obtener los cursos:', error);
    } finally {
        isLoading.value = false;
    }
};

const obtenerProfesores = async () => {
    try {
        const response = await axios.get('/teachers');
        teachers.value = response.data.teachers;
    } catch (error) {
        console.error('Error al obtener los profesores:', error);
    }
};

// Inicializar los datos cuando se monta el componente
onMounted(() => {
    obtenerCursos();
    obtenerProfesores();
});

const submit = async () => {
    isSubmitting.value = true;
    try {
        if (form.id) {
            await axios.put(`/course/${form.id}`, form);
            updatedCourseId.value = form.id;
        } else {
            const response = await axios.post('/course', form);
            updatedCourseId.value = response.data.id;
        }
        await obtenerCursos(currentPage.value);
        form.reset();
        await nextTick();
        updatedCourseId.value = null;
    } catch (error) {
        console.error('Error al guardar el curso:', error);
    } finally {
        isSubmitting.value = false;
    }
};

const editCourse = (course) => {
    form.id = course.id;
    form.nombre = course.nombre;
    form.descripcion = course.descripcion;
    form.id_profesor = course.id_profesor;
};

const confirmDeleteCourse = (course) => {
    courseToDelete.value = course;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
};

const deleteCourse = async () => {
    try {
        await axios.delete(`/course/${courseToDelete.value.id}`);
        await obtenerCursos(currentPage.value);
        courseToDelete.value = null;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    } catch (error) {
        console.error('Error al eliminar el curso:', error);
    }
};

const limpiarCampos = () => {
    form.reset();
};

const changePage = (page) => {
    if (page > 0 && page <= totalPages.value) {
        obtenerCursos(page);
    }
};
</script>

<template>
    <AppLayout title="Cursos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cursos
            </h2>
        </template>

        <div class="container mt-4">
            <form @submit.prevent="submit" class="mb-4 p-4 bg-white shadow-sm rounded">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-book"></i>
                            </span>
                            <input v-model="form.nombre" class="form-control" placeholder="Nombre del Curso" required />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <select v-model="form.id_profesor" class="form-control" required>
                                <option value="" disabled>Seleccione un Profesor</option>
                                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                    {{ teacher.nombre_completo }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-align-left"></i>
                        </span>
                        <textarea v-model="form.descripcion" class="form-control" placeholder="Descripción del Curso" rows="3"></textarea>
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
                <h3 class="mb-4">Lista de Cursos</h3>
                <div v-if="isLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <transition-group name="fade" tag="table" class="table table-hover" v-else>
                    <thead class="table-light" key="head">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Profesor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in courses" :key="course.id" :class="{ 'highlight': course.id === updatedCourseId }">
                            <td>{{ course.id }}</td>
                            <td>{{ course.nombre }}</td>
                            <td>{{ course.descripcion }}</td>
                            <td>{{ course.teacher?.nombre_completo }}</td>
                            <td>
                                <button class="btn btn-outline-warning btn-sm me-2" @click="editCourse(course)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" @click="confirmDeleteCourse(course)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </transition-group>
            </div>

            <!-- Paginación -->
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">
                            <i class="fas fa-chevron-left me-2"></i>Anterior
                        </a>
                    </li>
                    <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
                        <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">
                            Siguiente<i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el curso <strong>{{ courseToDelete?.nombre }}</strong>?</p>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="deleteCourse">
                            <i class="fas fa-trash-alt me-2"></i>Eliminar
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

.pagination {
    transition: all 0.3s ease-in-out;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control {
    border-left: none;
}

.input-group-text i {
    width: 16px;
    text-align: center;
}
</style>