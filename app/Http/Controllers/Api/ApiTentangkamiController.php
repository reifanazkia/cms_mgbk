<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tentangkami;

class ApiTentangkamiController extends Controller
{
    /**
     * Get all tentangkami data
     */
    public function index()
    {
        $data = Tentangkami::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List semua data tentang kami',
            'data' => $data
        ]);
    }

    /**
     * Get data by category
     * Category: Visi, Misi, Sejarah
     */
    public function byCategory($category)
    {
        $allowed = ['Visi', 'Misi', 'Sejarah'];

        if (!in_array($category, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak valid. Gunakan: Visi, Misi, atau Sejarah.'
            ], 422);
        }

        $data = Tentangkami::where('category', $category)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => "Data kategori: $category",
            'data' => $data
        ]);
    }
}
