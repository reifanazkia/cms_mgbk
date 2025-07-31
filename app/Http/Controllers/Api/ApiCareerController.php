<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class ApiCareerController extends Controller
{
    /**
     * Menampilkan daftar semua career.
     *
     * @group Career
     *
     * @response 200 {
     *   "status": true,
     *   "message": "List of careers",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "UI/UX Designer",
     *       "location": "Jakarta",
     *       "type": "Full Time",
     *       "created_at": "2025-07-28T10:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $careers = Career::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'List of careers',
            'data' => $careers
        ]);
    }

    /**
     * Menampilkan detail career berdasarkan ID.
     *
     * @group Career
     *
     * @urlParam id integer required ID Career. Contoh: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Career detail",
     *   "data": {
     *     "id": 1,
     *     "title": "UI/UX Designer",
     *     "location": "Jakarta",
     *     "type": "Full Time",
     *     "description": "Deskripsi pekerjaan...",
     *     "created_at": "2025-07-28T10:00:00.000000Z"
     *   }
     * }
     *
     * @response 404 {
     *   "status": false,
     *   "message": "Career not found"
     * }
     */
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
