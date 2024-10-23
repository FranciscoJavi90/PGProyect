<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function show(Role $role)
    {
        return response()->json($role);
    }

    public function edit(Role $role)
    {
        //
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}