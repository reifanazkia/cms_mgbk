<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class ApiCareerController extends Controller
{

    public function index()
    {
        $careers = Career::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'List of careers',
            'data' => $careers
        ]);
    }


    public function show($id)
    {
        $career = Career::with('applications')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Detail career berhasil diambil',
            'data' => $career
        ]);
    }
}
