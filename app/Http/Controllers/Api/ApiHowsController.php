<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hows;
use Illuminate\Http\Request;

class ApiHowsController extends Controller
{
    /**
     * Menampilkan semua data TKA (Tingkat Kesiapan Anggota).
     *
     * @group TKA (Tingkat Kesiapan Anggota)
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "step_number": 1,
     *     "title": "Langkah Pertama",
     *     "description": "Deskripsi langkah pertama",
     *     "image": "images/tka/step1.png",
     *     "created_at": "2025-07-01T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(Hows::orderBy('step_number')->get());
    }

    /**
     * Menampilkan detail TKA berdasarkan ID.
     *
     * @group TKA (Tingkat Kesiapan Anggota)
     *
     * @urlParam id integer required ID TKA. Contoh: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "step_number": 1,
     *   "title": "Langkah Pertama",
     *   "description": "Deskripsi langkah pertama",
     *   "image": "images/tka/step1.png",
     *   "created_at": "2025-07-01T10:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "Not found"
     * }
     */
    public function show($id)
    {
        $how = Hows::find($id);

        if (!$how) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($how);
    }
}
