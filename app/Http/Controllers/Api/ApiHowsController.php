<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hows;
use Illuminate\Http\Request;

class ApiHowsController extends Controller
{

    public function index()
    {
        return response()->json(Hows::orderBy('step_number')->get());
    }

    public function show($id)
    {
        $how = Hows::find($id);

        if (!$how) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($how);
    }
}
