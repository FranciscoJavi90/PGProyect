<?php

namespace App\Http\Controllers;

use App\Models\AnalysisIA;
use Illuminate\Http\Request;

class AnalysisIAController extends Controller
{
    public function store(Request $request)
    {
        $analysisIA = AnalysisIA::create($request->all());
        return response()->json($analysisIA, 201);
    }
}