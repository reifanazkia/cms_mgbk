<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OurBlog;
use Illuminate\Http\Request;

class ApiOurblogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ourblog",
     *     operationId="getAllOurBlogs",
     *     tags={"OurBlog"},
     *     summary="Menampilkan daftar OurBlog",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar OurBlog",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data OurBlog berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/OurBlog")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $ourblogs = OurBlog::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data OurBlog berhasil diambil',
            'data' => $ourblogs
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/ourblog/{id}",
     *     operationId="getOurBlogById",
     *     tags={"OurBlog"},
     *     summary="Menampilkan detail OurBlog berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID OurBlog",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data OurBlog",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail OurBlog berhasil diambil"),
     *             @OA\Property(property="data", ref="#/components/schemas/OurBlog")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="OurBlog tidak ditemukan",
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
        $ourblog = OurBlog::with('category')->find($id);

        if (!$ourblog) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail OurBlog berhasil diambil',
            'data' => $ourblog
        ]);
    }
}
