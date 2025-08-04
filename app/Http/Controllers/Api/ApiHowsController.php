<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hows;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Hows",
 *     type="object",
 *     title="Hows",
 *     required={"step_number", "title"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="step_number", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Langkah 1"),
 *     @OA\Property(property="description", type="string", example="Deskripsi langkah 1"),
 *     @OA\Property(property="image", type="string", example="storage/kta/image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ApiHowsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hows",
     *     operationId="getAllHows",
     *     tags={"Hows"},
     *     summary="Menampilkan daftar Hows",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar Hows",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Hows")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Hows::orderBy('step_number')->get());
    }

    /**
     * @OA\Get(
     *     path="/api/hows/{id}",
     *     operationId="getHowsById",
     *     tags={"Hows"},
     *     summary="Menampilkan detail Hows berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Hows",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data Hows",
     *         @OA\JsonContent(ref="#/components/schemas/Hows")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hows tidak ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $how = Hows::find($id);

        if (!$how) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($how);
    }
}
