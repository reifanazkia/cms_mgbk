<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Career",
 *     type="object",
 *     required={"id", "job_type", "position_title", "lokasi", "pengalaman", "jam_kerja", "hari_kerja", "ringkasan"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="job_type", type="string", example="Full Time"),
 *     @OA\Property(property="position_title", type="string", example="Software Engineer"),
 *     @OA\Property(property="lokasi", type="string", example="Bandung"),
 *     @OA\Property(property="pengalaman", type="string", example="2 tahun"),
 *     @OA\Property(property="jam_kerja", type="string", example="08:00 - 17:00"),
 *     @OA\Property(property="hari_kerja", type="string", example="Senin - Jumat"),
 *     @OA\Property(property="ringkasan", type="string", example="Bertanggung jawab atas pengembangan aplikasi."),
 *     @OA\Property(
 *         property="klasifikasi",
 *         type="array",
 *         @OA\Items(type="string", example="IT")
 *     ),
 *     @OA\Property(
 *         property="deskripsi",
 *         type="array",
 *         @OA\Items(type="string", example="Membuat dan menguji fitur.")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ApiCareerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/career",
     *     operationId="getAllCareers",
     *     tags={"Career"},
     *     summary="Menampilkan daftar Career",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar Career",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List of careers"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Career")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $careers = Career::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'List of careers',
            'data' => $careers
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/career/{id}",
     *     operationId="getCareerById",
     *     tags={"Career"},
     *     summary="Menampilkan detail Career berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Career",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data Career",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Career detail"),
     *             @OA\Property(property="data", ref="#/components/schemas/Career")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Career tidak ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Career not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $career = Career::find($id);

        if (!$career) {
            return response()->json([
                'status' => false,
                'message' => 'Career not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Career detail',
            'data' => $career
        ]);
    }
}
