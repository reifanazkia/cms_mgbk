<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class ApiKegiatanController extends Controller
{
    /**
     * Menampilkan daftar semua kegiatan beserta kategori.
     *
     * @group Kegiatan
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Data Kegiatan berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Pelatihan Leadership",
     *       "date": "2025-08-15",
     *       "location": "Aula Utama",
     *       "category": {
     *         "id": 3,
     *         "name": "Workshop"
     *       }
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $kegiatan = Kegiatan::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Kegiatan berhasil diambil',
            'data' => $kegiatan
        ]);
    }

    /**
     * Menampilkan detail satu kegiatan berdasarkan ID.
     *
     * @group Kegiatan
     *
     * @urlParam id integer required ID Kegiatan. Contoh: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Detail Kegiatan berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "title": "Pelatihan Leadership",
     *     "date": "2025-08-15",
     *     "location": "Aula Utama",
     *     "description": "Deskripsi lengkap pelatihan…",
     *     "category": {
     *       "id": 3,
     *       "name": "Workshop"
     *     }
     *   }
     * }
     *
     * @response 404 {
     *   "status": false,
     *   "message": "Data tidak ditemukan",
     *   "data": null
     * }
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::with('category')->find($id);

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Kegiatan berhasil diambil',
            'data' => $kegiatan
        ]);
    }
}
