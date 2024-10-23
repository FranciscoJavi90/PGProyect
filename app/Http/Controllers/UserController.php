<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importar el facade Auth

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    // Nuevo método para obtener el usuario autenticado
    public function authenticatedUser()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}