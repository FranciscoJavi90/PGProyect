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

const students = ref([]);
const courses = ref([]);
const form = useForm({
    id: '',
    clave: '',
    nombre: '',
    apellidos: '',
    fecha_nacimiento: '',
    CUI: '',
    genero: '',
    id_curso: ''
});
const studentToDelete = ref(null);
const currentPage = ref(1);
const totalPages = ref(1);
const isLoading = ref(true);
const isSubmitting = ref(false);
const updatedStudentId = ref(null);

const obtenerEstudiantes = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/estudiantes?page=${page}`);
        students.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error('Error al obtener los estudiantes:', error);
    } finally {
        isLoading.value = false;
    }
};

const obtenerCursos = async () => {
    try {
        const response = await axios.get('/cursos/all');
        courses.value = response.data.courses;
    } catch (error) {
        console.error('Error al obtener los cursos:', error);
    }
};

// Inicializar los datos cuando se monta el componente
onMounted(() => {
    obtenerEstudiantes();
    obtenerCursos();
});

const submit = async () => {
    isSubmitting.value = true;
    try {
        if (form.id) {
            await axios.put(`/estudiantes/${form.id}`, form);
            updatedStudentId.value = form.id;
        } else {
            const response = await axios.post('/estudiantes', form);
            updatedStudentId.value = response.data.id;
        }
        await obtenerEstudiantes(currentPage.value);
        form.reset();
        await nextTick();
        updatedStudentId.value = null;
    } catch (error) {
        console.error('Error al guardar el estudiante:', error);
    } finally {
        isSubmitting.value = false;
    }
};

const editStudent = (student) => {
    form.id = student.id;
    form.clave = student.clave;
    form.nombre = student.nombre;
    form.apellidos = student.apellidos;
    form.fecha_nacimiento = student.fecha_nacimiento;
    form.CUI = student.CUI;
    form.genero = student.genero;
    form.id_curso = student.id_curso;
};

const confirmDeleteStudent = (student) => {
    studentToDelete.value = student;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
};

const deleteStudent = async () => {
    try {
        await axios.delete(`/estudiantes/${studentToDelete.value.id}`);
        await obtenerEstudiantes(currentPage.value);
        studentToDelete.value = null;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    } catch (error) {
        console.error('Error al eliminar el estudiante:', error);
    }
};

const limpiarCampos = () => {
    form.reset();
};

const changePage = (page) => {
    if (page > 0 && page <= totalPages.value) {
        obtenerEstudiantes(page);
    }
};
</script>

<template>
    <AppLayout title="Estudiantes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Estudiantes
            </h2>
        </template>

        <div class="container mt-4">
            <form @submit.prevent="submit" class="mb-4 p-4 bg-white shadow-sm rounded">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input v-model="form.clave" class="form-control" placeholder="Clave del Estudiante" required />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input v-model="form.nombre" class="form-control" placeholder="Nombre" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <input v-model="form.apellidos" class="form-control" placeholder="Apellidos" required />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input v-model="form.fecha_nacimiento" type="date" class="form-control" placeholder="Fecha de Nacimiento" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input v-model="form.CUI" class="form-control" placeholder="CUI" required />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            <select v-model="form.genero" class="form-control" required>
                                <option value="" disabled>Seleccione Género</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <select v-model="form.id_curso" class="form-control" required>
                                <option value="" disabled>Seleccione un Curso</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.nombre }} - Profesor:
                                    <span v-if="course.teacher">
                                        {{ course.teacher.nombre_completo }}
                                    </span>
                                    <span v-else>
                                        Sin profesor
                                    </span>
                                </option>
                            </select>
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
                        <i class="fas fa-broom me-2"></i>Limpiar
                    </button>
                </div>
            </form>

            <div class="bg-white shadow-sm rounded p-4">
                <h3 class="mb-4">Lista de Estudiantes</h3>
                <div v-if="isLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div class="table-responsive" v-else>
                    <transition-group name="fade" tag="table" class="table table-hover">
                        <thead class="table-light" key="head">
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Fecha de Nacimiento</th>
                                <th>CUI</th>
                                <th>Género</th>
                                <th>Curso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id" :class="{ 'highlight': student.id === updatedStudentId }">
                                <td>{{ student.id }}</td>
                                <td>{{ student.clave }}</td>
                                <td>{{ student.nombre }}</td>
                                <td>{{ student.apellidos }}</td>
                                <td>{{ student.fecha_nacimiento }}</td>
                                <td>{{ student.CUI }}</td>
                                <td>{{ student.genero }}</td>
                                <td>{{ student.course.nombre }}</td>
                                <td>
                                    <button class="btn btn-outline-warning btn-sm me-2" @click="editStudent(student)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" @click="confirmDeleteStudent(student)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </transition-group>
                </div>
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
                        <p>¿Estás seguro de que deseas eliminar al estudiante <strong>{{ studentToDelete?.nombre }} {{ studentToDelete?.apellidos }}</strong>?</p>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="deleteStudent">
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
    background-color:  #e9ecef;
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