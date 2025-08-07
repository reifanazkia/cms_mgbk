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
        $career = Career::find($id);

        if (!$career) {
            return response()->json([
                'status' => false,
                'message' => 'Career not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Career detail',
            'data' => $career
        ]);
    }
}
