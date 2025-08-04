<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Kegiatan",
 *     title="Kegiatan",
 *     description="Schema untuk entitas Kegiatan",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="category_kegiatan_id", type="integer", example=2),
 *     @OA\Property(property="title", type="string", example="Pelatihan Web Laravel"),
 *     @OA\Property(property="description", type="string", example="Deskripsi pelatihan lengkap"),
 *     @OA\Property(property="image", type="string", example="storage/kegiatan/image1.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-04T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-04T10:30:00Z")
 * )
 */
class ApiKegiatanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/kegiatan",
     *     operationId="getAllKegiatan",
     *     tags={"Kegiatan"},
     *     summary="Menampilkan daftar Kegiatan",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar Kegiatan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Kegiatan berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Kegiatan")
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/kegiatan/{id}",
     *     operationId="getKegiatanById",
     *     tags={"Kegiatan"},
     *     summary="Menampilkan detail Kegiatan berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Kegiatan",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data Kegiatan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail Kegiatan berhasil diambil"),
     *             @OA\Property(property="data", ref="#/components/schemas/Kegiatan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kegiatan tidak ditemukan",
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
