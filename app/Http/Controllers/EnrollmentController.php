<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $enrollment = Enrollment::create($request->all());
        return response()->json($enrollment, 201);
    }
}