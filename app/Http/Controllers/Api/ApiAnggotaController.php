<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

class ApiAnggotaController extends Controller
{
    /**
     * Menampilkan semua anggota beserta kategori.
     *
     * @group Anggota
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Data Anggota berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Dian Permata",
     *       "email": "dian@example.com",
     *       "category": {
     *         "id": 1,
     *         "name": "Pengurus"
     *       }
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $anggota = Anggota::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Anggota berhasil diambil',
            'data' => $anggota
        ]);
    }

    /**
     * Menampilkan detail anggota berdasarkan ID.
     *
     * @group Anggota
     *
     * @urlParam id integer required ID Anggota. Contoh: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Detail Anggots berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "name": "Dian Permata",
     *     "email": "dian@example.com",
     *     "category": {
     *       "id": 1,
     *       "name": "Pengurus"
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
        $anggota = Anggota::with('category')->find($id);

        if (!$anggota) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Anggots berhasil diambil',
            'data' => $anggota
        ]);
    }
}
