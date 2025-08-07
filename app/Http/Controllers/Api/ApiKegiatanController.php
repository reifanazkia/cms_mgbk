<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;


class ApiKegiatanController extends Controller
{

    public function index()
    {
        $kegiatan = Kegiatan::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Kegiatan berhasil diambil',
            'data' => $kegiatan
        ]);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with('category')->find($id);

        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Kegiatan berhasil diambil',
            'data' => $kegiatan
        ]);
    }
}
