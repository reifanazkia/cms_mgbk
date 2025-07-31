<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

/**
 * @group Slider API
 *
 * Endpoint untuk mengambil data slider yang ditampilkan di halaman utama.
 */
class ApiSliderController extends Controller
{
    /**
     * List Slider
     *
     * Menampilkan semua data slider.
     *
     * @response 200 {
     *   "status": true,
     *   "message": "List of Slider",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Judul Slider",
     *       "subtitle": "Subjudul",
     *       "image": "slider.jpg",
     *       "created_at": "2025-07-29T12:00:00.000000Z",
     *       "updated_at": "2025-07-29T12:00:00.000000Z"
     *     }
     *   ]
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $slider = Slider::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'List of Slider',
            'data' => $slider
        ]);
    }
}
