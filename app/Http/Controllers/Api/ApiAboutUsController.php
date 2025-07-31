<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class ApiAboutUsController extends Controller
{
    /**
     * Menampilkan semua data About Us.
     *
     * @group About Us
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Data About Us berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Tentang Kami",
     *       "description": "Isi tentang kami...",
     *       "photos": [
     *         {
     *           "id": 10,
     *           "about_us_id": 1,
     *           "image": "images/about/1.jpg"
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $abouts = AboutUs::with('photos')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data About Us berhasil diambil',
            'data' => $abouts
        ]);
    }

    /**
     * Menampilkan detail About Us berdasarkan ID.
     *
     * @group About Us
     *
     * @urlParam id integer required ID About Us. Contoh: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Detail About Us berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "title": "Tentang Kami",
     *     "description": "Isi tentang kami...",
     *     "photos": [
     *       {
     *         "id": 10,
     *         "about_us_id": 1,
     *         "image": "images/about/1.jpg"
     *       }
     *     ]
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
        $about = AboutUs::with('photos')->find($id);

        if (!$about) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail About Us berhasil diambil',
            'data' => $about
        ]);
    }
}
