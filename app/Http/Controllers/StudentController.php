<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $students = Student::with('course')->paginate(10);
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        try {
            $request->validate([
                'clave' => 'required|string|max:100|unique:alumnos,clave',
                'nombre' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'fecha_nacimiento' => 'required|date',
                'CUI' => 'required|string|max:13|unique:alumnos,CUI',
                'genero' => 'required|string|max:20',
                'id_curso' => 'required|exists:cursos,id',
            ]);

            Student::create([
                'clave' => $request->clave,
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'CUI' => $request->CUI,
                'genero' => $request->genero,
                'id_curso' => $request->id_curso,
                'id_usuario' => Auth::id(),
            ]);

            return response()->json(['message' => 'Estudiante creado exitosamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor. Por favor, inténtelo de nuevo más tarde.'], 500);
        }
    }

    public function show(Student $student)
    {
        return response()->json($student->load('course'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'clave' => 'required|string|max:100|unique:alumnos,clave,' . $student->id,
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'CUI' => 'required|string|max:13|unique:alumnos,CUI,' . $student->id,
            'genero' => 'required|string|max:20',
            'id_curso' => 'required|exists:cursos,id',
        ]);

        $student->update([
            'clave' => $request->clave,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'CUI' => $request->CUI,
            'genero' => $request->genero,
            'id_curso' => $request->id_curso,
        ]);

        return response()->json(['message' => 'Estudiante actualizado exitosamente']);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Estudiante eliminado exitosamente']);
    }
}