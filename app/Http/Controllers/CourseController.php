<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $courses = Course::with('teacher')->paginate(10); // Paginación de 10 registros por página
            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Cargar ddl cursos
    public function all()
    {
        try {
            $courses = Course::with('teacher')->get();
            return response()->json(['courses' => $courses]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Datos recibidos para crear curso:', $request->all());
            $course = Course::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_profesor' => $request->id_profesor,
            ]);

            return response()->json($course, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear curso:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Course $course)
    {
        try {
            $course->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_profesor' => $request->id_profesor,
            ]);

            return response()->json($course);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el curso:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}