<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaSpeaker;
use Illuminate\Support\Facades\Storage;

class AgendaSpeakerController extends Controller
{
    public function index()
    {
        $speakers = AgendaSpeaker::all();
        return view('agenda-speakers.index', compact('speakers'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Siapkan data
            $data = [
                'name' => $request->name,
                'title' => $request->title ?? null,
            ];

            // Handle upload foto
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('agenda-speakers', $filename, 'public');
                $data['photo'] = $path;
            }

            // Simpan ke database
            $speaker = AgendaSpeaker::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Speaker berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $speaker = AgendaSpeaker::find($id);

        if (!$speaker) {
            return response()->json([
                'success' => false,
                'message' => 'Speaker tidak ditemukan'
            ], 404);
        }

        return response()->json($speaker);
    }

    public function update(Request $request, $id)
    {
        $speaker = AgendaSpeaker::find($id);

        if (!$speaker) {
            return response()->json([
                'success' => false,
                'message' => 'Speaker tidak ditemukan'
            ], 404);
        }

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Update data dasar
            $speaker->name = $request->name;
            $speaker->title = $request->title ?? null;

            // Handle upload foto baru
            if ($request->hasFile('photo')) {
                // Hapus foto lama
                if ($speaker->photo && Storage::disk('public')->exists($speaker->photo)) {
                    Storage::disk('public')->delete($speaker->photo);
                }

                // Upload foto baru
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('agenda-speakers', $filename, 'public');
                $speaker->photo = $path;
            }

            $speaker->save();

            return response()->json([
                'success' => true,
                'message' => 'Speaker berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $speaker = AgendaSpeaker::find($id);

        if (!$speaker) {
            return response()->json([
                'success' => false,
                'message' => 'Speaker tidak ditemukan'
            ], 404);
        }

        try {
            // Hapus foto jika ada
            if ($speaker->photo && Storage::disk('public')->exists($speaker->photo)) {
                Storage::disk('public')->delete($speaker->photo);
            }

            $speaker->delete();

            return response()->json([
                'success' => true,
                'message' => 'Speaker berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        try {
            $speakers = AgendaSpeaker::whereIn('id', $ids)->get();

            // Hapus foto-foto
            foreach ($speakers as $speaker) {
                if ($speaker->photo && Storage::disk('public')->exists($speaker->photo)) {
                    Storage::disk('public')->delete($speaker->photo);
                }
            }

            // Hapus dari database
            AgendaSpeaker::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', count($ids) . ' speaker berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
