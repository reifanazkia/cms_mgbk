<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::with('career:id,position_title')->latest()->paginate(10);
        $careers = Career::select('id', 'position_title')->get();

        return view('applications.index', compact('applications', 'careers'));
    }

    /**
     * Get data for modal create form
     */
    public function create()
    {
        $careers = Career::select('id', 'position_title')->get();

        return response()->json([
            'success' => true,
            'careers' => $careers
        ]);
    }

    /**
     * Store a newly created resource in storage.
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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['career_id', 'nama', 'email', 'no_telepon', 'cover_letter']);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('applications', $filename, 'public');
            $data['file'] = $path;
        }

        $application = Application::create($data);
        $application->load('career:id,position_title');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil dikirim!',
                'data' => $application
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Lamaran berhasil dikirim!');
    }

    /**
     * Get data for modal show/detail
     */
    public function show(Application $application)
    {
        $application->load('career:id,position_title');

        return response()->json([
            'success' => true,
            'data' => $application
        ]);
    }

    /**
     * Get data for modal edit form
     */
    public function edit(Application $application)
    {
        $careers = Career::select('id', 'position_title')->get();
        $application->load('career:id,position_title');

        return response()->json([
            'success' => true,
            'data' => $application,
            'careers' => $careers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'career_id' => 'required|exists:careers,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'cover_letter' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['career_id', 'nama', 'email', 'no_telepon', 'cover_letter']);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($application->file && Storage::disk('public')->exists($application->file)) {
                Storage::disk('public')->delete($application->file);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('applications', $filename, 'public');
            $data['file'] = $path;
        }

        $application->update($data);
        $application->load('career:id,position_title');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data lamaran berhasil diperbarui!',
                'data' => $application
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Data lamaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        // Delete file if exists
        if ($application->file && Storage::disk('public')->exists($application->file)) {
            Storage::disk('public')->delete($application->file);
        }

        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data lamaran berhasil dihapus!'
        ]);
    }

    /**
     * Download the uploaded file
     */
    public function downloadFile(Application $application)
    {
        if (!$application->file || !Storage::disk('public')->exists($application->file)) {
            abort(404, 'File tidak ditemukan');
        }

        // Path absolut ke file di storage/app/public/...
        $absolutePath = Storage::disk('public')->path($application->file);

        // Nama file untuk pengguna saat diunduh
        $storedName   = pathinfo($application->file, PATHINFO_BASENAME);

        // Kalau kamu menyimpan dengan format "time()_original.ext",
        // baris di bawah akan menghapus prefix angka + underscore.
        $downloadName = preg_replace('/^\d+_/', '', $storedName);

        return response()->download($absolutePath, $downloadName);
    }

    /**
     * Bulk delete applications
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipilih'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        $applications = Application::whereIn('id', $ids)->get();

        foreach ($applications as $application) {
            // Delete file if exists
            if ($application->file && Storage::disk('public')->exists($application->file)) {
                Storage::disk('public')->delete($application->file);
            }
        }

        Application::whereIn('id', $ids)->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data lamaran yang dipilih berhasil dihapus!'
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Data lamaran yang dipilih berhasil dihapus!');
    }
}
