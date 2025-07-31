<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductsController extends Controller
{
    /**
     * @group Produk
     *
     * GET List of Products
     *
     * Ambil semua data produk digital yang tersedia.
     *
     * @response 200 scenario="success" {
     *   "status": true,
     *   "message": "List of Products",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Produk A",
     *       "price": 10000,
     *       "discount": 10,
     *       "created_at": "2025-07-29T00:00:00.000000Z",
     *       ...
     *     }
     *   ]
     * }
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
     * @group Produk
     *
     * GET Product Detail
     *
     * Ambil detail produk berdasarkan ID.
     *
     * @urlParam id integer required ID produk. Contoh: 1
     *
     * @response 200 scenario="success" {
     *   "status": true,
     *   "message": "Product detail",
     *   "data": {
     *     "id": 1,
     *     "title": "Produk A",
     *     "price": 10000,
     *     "discount": 10,
     *     "created_at": "2025-07-29T00:00:00.000000Z",
     *     ...
     *   }
     * }
     *
     * @response 404 scenario="not found" {
     *   "status": false,
     *   "message": "Product not found"
     * }
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
