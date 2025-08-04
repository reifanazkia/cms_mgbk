<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/product",
     *     operationId="getAllProducts",
     *     tags={"Product"},
     *     summary="Menampilkan daftar Product",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar Product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List of Products"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $product = Product::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'List of Products',
            'data' => $product
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/product/{id}",
     *     operationId="getProductById",
     *     tags={"Product"},
     *     summary="Menampilkan detail Product berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data Product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product detail"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product tidak ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product detail',
            'data' => $product
        ]);
    }
}
