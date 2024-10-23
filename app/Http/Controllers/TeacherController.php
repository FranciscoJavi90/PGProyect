<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json(['teachers' => $teachers]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'titulo_profesional' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'numero_empleado' => 'required|integer',
        ]);

        Teacher::create([
            'nombre_completo' => $request->nombre_completo,
            'titulo_profesional' => $request->titulo_profesional,
            'especialidad' => $request->especialidad,
            'numero_empleado' => $request->numero_empleado,
            'id_usuario' => Auth::id(),
        ]);

        return response()->json(['message' => 'Profesor creado exitosamente']);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'titulo_profesional' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'numero_empleado' => 'required|integer',
        ]);

        $teacher->update([
            'nombre_completo' => $request->nombre_completo,
            'titulo_profesional' => $request->titulo_profesional,
            'especialidad' => $request->especialidad,
            'numero_empleado' => $request->numero_empleado,
        ]);

        return response()->json(['message' => 'Profesor actualizado exitosamente']);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json(['message' => 'Profesor eliminado exitosamente']);
    }
}