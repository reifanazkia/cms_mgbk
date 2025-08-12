<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tentangkami;
use App\Models\TentangkamiCategory;
use Illuminate\Support\Facades\Log;

class ApiTentangkamiController extends Controller
{
    // GET semua data Tentangkami
    public function index()
    {
        try {
            $tentangkami = Tentangkami::with('category')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tentangkami
            ]);
        } catch (\Exception $e) {
            Log::error('API Tentangkami@index: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data'], 500);
        }
    }

    // GET Tentangkami berdasarkan category_id
    public function getByCategory($categoryId)
    {
        try {
            $tentangkami = Tentangkami::with('category')
                ->where('category_tentangkami_id', $categoryId)
                ->latest()
                ->get();

            return response()->json(['success' => true, 'data' => $tentangkami]);
        } catch (\Exception $e) {
            Log::error('API Tentangkami@getByCategory: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data'], 500);
        }
    }

    // GET Tentangkami berdasarkan nama kategori
    public function getByCategoryName($categoryName)
    {
        try {
            $category = TentangkamiCategory::where('name', $categoryName)->first();

            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan'], 404);
            }

            $tentangkami = Tentangkami::with('category')
                ->where('category_tentangkami_id', $category->id)
                ->latest()
                ->get();

            return response()->json(['success' => true, 'data' => $tentangkami]);
        } catch (\Exception $e) {
            Log::error('API Tentangkami@getByCategoryName: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data'], 500);
        }
    }

    // GET Tentangkami yang tampil di homepage
    public function getDisplayOnHome()
    {
        try {
            $tentangkami = Tentangkami::with('category')
                ->where('display_on_home', true)
                ->latest()
                ->get();

            return response()->json(['success' => true, 'data' => $tentangkami]);
        } catch (\Exception $e) {
            Log::error('API Tentangkami@getDisplayOnHome: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data'], 500);
        }
    }
}
