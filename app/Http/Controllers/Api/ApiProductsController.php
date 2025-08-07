<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductsController extends Controller
{
    public function index()
    {
        $product = Product::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'List of Products',
            'data' => $product
        ]);
    }

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
