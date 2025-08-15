<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ApiApplicationController extends Controller
{
    /**
     * GET: Ambil semua lamaran kerja dengan career terkait (paginate)
     */
    public function index()
    {
        $applications = Application::with('career:id,position_title')
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Daftar lamaran berhasil diambil',
            'data' => $applications
        ]);
    }

    /**
     * GET: Detail lamaran kerja
     */
    public function show($id)
    {
        $application = Application::with('career:id,position_title')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Detail lamaran berhasil diambil',
            'data' => $application
        ]);
    }

    /**
     * POST: Kirim lamaran kerja
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'career_id' => 'required|exists:careers,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'cover_letter' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['career_id', 'nama', 'email', 'no_telepon', 'cover_letter']);

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('applications', $filename, 'public');
            $data['file'] = $path;
        }

        $application = Application::create($data);
        $application->load('career:id,position_title');

        return response()->json([
            'status' => true,
            'message' => 'Lamaran berhasil dikirim!',
            'data' => $application
        ]);
    }
}
