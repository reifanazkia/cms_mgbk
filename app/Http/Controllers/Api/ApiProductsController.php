<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CategoryStore;

class ApiProductsController extends Controller
{
    /**
     * Ambil semua produk beserta kategori
     */
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Ambil detail produk berdasarkan ID
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Ambil produk berdasarkan kategori
     */
    public function getByCategory($categoryId)
    {
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
            'category' => $category,
            'data' => $products
        ]);
    }
}
