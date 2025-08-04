<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Anggota",
 *     type="object",
 *     required={"id", "name", "title", "category_anggota_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="category_anggota_id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Andi Saputra"),
 *     @OA\Property(property="title", type="string", example="Koordinator MGBK"),
 *     @OA\Property(property="email", type="string", example="andi@example.com"),
 *     @OA\Property(property="phone_number", type="string", example="081234567890"),
 *     @OA\Property(property="facebook_id", type="string", example="andi.saputra"),
 *     @OA\Property(property="instagram_id", type="string", example="andi_ig"),
 *     @OA\Property(property="tiktok_id", type="string", example="andi.tiktok"),
 *     @OA\Property(property="image", type="string", example="anggota/foto.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T10:00:00Z"),
 *     @OA\Property(
 *         property="category",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="name", type="string", example="Pengurus Wilayah")
 *     )
 * )
 */
class ApiAnggotaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/anggota",
     *     operationId="getAllAnggota",
     *     tags={"Anggota"},
     *     summary="Menampilkan daftar seluruh Anggota",
     *     description="Mengembalikan list data anggota beserta relasi kategori.",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data anggota",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Anggota berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Anggota")
     *             )
     *         )
     *     )
     * )
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
}
