<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="AboutUs",
 *     type="object",
 *     title="About Us",
 *     required={"id", "sejarah", "visi", "misi"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="sejarah", type="string", example="Sejarah organisasi..."),
 *     @OA\Property(property="visi", type="string", example="Menjadi yang terbaik..."),
 *     @OA\Property(property="misi", type="string", example="Memberikan layanan..."),
 *     @OA\Property(
 *         property="photos",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=5),
 *             @OA\Property(property="photo_path", type="string", example="storage/aboutus/image.jpg")
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ApiAboutUsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/about-us",
     *     operationId="getAllAboutUs",
     *     summary="Menampilkan semua data About Us",
     *     tags={"About Us"},
     *     @OA\Response(
     *         response=200,
     *         description="Sukses ambil semua About Us",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data About Us berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AboutUs")
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/about-us/{id}",
     *     operationId="getAboutUsById",
     *     summary="Menampilkan detail About Us berdasarkan ID",
     *     tags={"About Us"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID About Us",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sukses ambil detail About Us",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail About Us berhasil diambil"),
     *             @OA\Property(property="data", ref="#/components/schemas/AboutUs")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data tidak ditemukan"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
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
