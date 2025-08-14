<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Product;
use Illuminate\Http\JsonResponse;


class ApiProductsController extends Controller
{
    /**
     * Ambil semua produk beserta kategori
     */
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('category')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil detail produk berdasarkan ID
     */
    public function show($id): JsonResponse
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail produk berhasil diambil',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil produk berdasarkan kategori
     */
    public function getByCategory($categoryId): JsonResponse
    {
        try {
            $category = CategoryStore::find($categoryId);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $products = Product::where('category_store_id', $categoryId)
                ->with('category')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Produk berdasarkan kategori berhasil diambil',
                'category' => $category,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil produk berdasarkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
