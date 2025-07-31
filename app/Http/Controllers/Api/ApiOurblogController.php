<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ourblog;
use Illuminate\Http\Request;

class ApiOurblogController extends Controller
{
    /**
     * Get all our blogs
     *
     * Mengambil semua data ourblog beserta kategori.
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Data About Us berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Judul Blog",
     *       "slug": "judul-blog",
     *       "category": {
     *         "id": 1,
     *         "name": "Kategori A"
     *       },
     *       "created_at": "2025-07-28T10:00:00.000000Z",
     *       "updated_at": "2025-07-28T10:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $blog = Ourblog::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data About Us berhasil diambil',
            'data' => $blog
        ]);
    }

    /**
     * Get detail of an our blog
     *
     * Mengambil detail sebuah ourblog berdasarkan ID-nya.
     *
     * @urlParam id int required ID dari blog yang ingin ditampilkan. Contoh: 1
     * @response 200 {
     *   "status": true,
     *   "message": "Detail About Us berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "title": "Judul Blog",
     *     "slug": "judul-blog",
     *     "category": {
     *       "id": 1,
     *       "name": "Kategori A"
     *     },
     *     "created_at": "2025-07-28T10:00:00.000000Z",
     *     "updated_at": "2025-07-28T10:00:00.000000Z"
     *   }
     * }
     * @response 404 {
     *   "status": false,
     *   "message": "Data tidak ditemukan",
     *   "data": null
     * }
     */
    public function show($id)
    {
        $blog = Ourblog::with('category')->find($id);

        if (!$blog) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail About Us berhasil diambil',
            'data' => $blog
        ]);
    }
}
