<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class ApiAgendaController extends Controller
{
    /**
     * Menampilkan semua agenda beserta pembicara.
     *
     * @group Agenda
     *
     * @response 200 {
     *   "success": true,
     *   "message": "List agenda",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Seminar Nasional",
     *       "date": "2025-08-01",
     *       "location": "Universitas ABC",
     *       "speakers": [
     *         {
     *           "id": 1,
     *           "name": "Dr. Budi Santosa",
     *           "position": "Dosen Psikologi"
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function index()
    {
        // Ambil semua agenda beserta pembicara
        $agendas = Agenda::with('speakers')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List agenda',
            'data' => $agendas
        ]);
    }

    /**
     * Menampilkan detail satu agenda berdasarkan ID.
     *
     * @group Agenda
     *
     * @urlParam id integer required ID agenda. Contoh: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Detail agenda",
     *   "data": {
     *     "id": 1,
     *     "title": "Seminar Nasional",
     *     "date": "2025-08-01",
     *     "location": "Universitas ABC",
     *     "speakers": [
     *       {
     *         "id": 1,
     *         "name": "Dr. Budi Santosa",
     *         "position": "Dosen Psikologi"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Agenda tidak ditemukan"
     * }
     */
    public function show($id)
    {
        $agenda = Agenda::with('speakers')->find($id);

        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail agenda',
            'data' => $agenda
        ]);
    }
}
