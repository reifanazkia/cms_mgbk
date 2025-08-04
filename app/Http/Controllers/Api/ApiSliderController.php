<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Slider",
 *     title="Slider",
 *     description="Schema untuk entitas Slider",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Selamat Datang di Platform Kami"),
 *     @OA\Property(property="subtitle", type="string", example="Pelatihan dan Modul Terbaik"),
 *     @OA\Property(property="image", type="string", example="storage/sliders/slider1.jpg"),
 *     @OA\Property(property="youtube_id", type="string", example="dQw4w9WgXcQ"),
 *     @OA\Property(property="button_text", type="string", example="Pelajari Lebih Lanjut"),
 *     @OA\Property(property="url_link", type="string", example="https://example.com/module"),
 *     @OA\Property(property="display_on_home", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-04T10:30:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-04T10:45:00Z")
 * )
 */
class ApiSliderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/slider",
     *     operationId="getAllSliders",
     *     tags={"Slider"},
     *     summary="Ambil semua data slider untuk admin",
     *     description="Mengembalikan list semua slider dari database, untuk tampilan admin.",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data slider",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List of Slider"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Slider")
     *             )
     *         )
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/api/slider/home",
     *     operationId="getHomeSliders",
     *     tags={"Slider"},
     *     summary="Ambil slider untuk homepage",
     *     description="Menampilkan semua slider yang tampil di homepage",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil slider homepage",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Slider")
     *             )
     *         )
     *     )
     * )
     */
    public function showHomeSlider(): JsonResponse
    {
        $sliders = Slider::where('display_on_home', true)->latest()->get();
        return response()->json([
            'status' => true,
            'data' => $sliders,
        ]);
    }
}
