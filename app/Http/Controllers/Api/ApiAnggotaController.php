<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

class ApiAnggotaController extends Controller
{
    
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
