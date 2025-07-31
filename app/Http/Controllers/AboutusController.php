<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\Aboutus_photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AboutUsController extends Controller
{
    public function index()
    {
        $abouts = AboutUs::with('photos')->latest()->get();
        return view('about.index', compact('abouts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sejarah' => 'required',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $about = AboutUs::create([
            'sejarah' => $request->sejarah,
            'visi' => $request->visi,
            'misi' => $request->misi,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('aboutus', 'public');
                    Aboutus_photo::create([
                        'aboutus_id' => $about->id,
                        'photo_path' => 'storage/' . $path,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $about = AboutUs::with('photos')->findOrFail($id);
        return response()->json($about);
    }

    public function update(Request $request, $id)
    {
        // Debug log untuk melihat data yang diterima
        Log::info('Update request data:', $request->all());

        $about = AboutUs::findOrFail($id);

        $request->validate([
            'sejarah' => 'required',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data utama
        $about->update([
            'sejarah' => $request->sejarah,
            'visi' => $request->visi,
            'misi' => $request->misi,
        ]);

        // Hapus foto yang dipilih untuk dihapus
        if ($request->filled('photo_ids_to_delete')) {
            $photoIdsToDelete = array_filter(explode(',', $request->photo_ids_to_delete));
            Log::info('Photo IDs to delete:', ['ids' => $photoIdsToDelete]);

            foreach ($photoIdsToDelete as $photoId) {
                $photoId = trim($photoId);
                if (!empty($photoId) && is_numeric($photoId)) {
                    $photo = Aboutus_photo::where('id', $photoId)
                        ->where('aboutus_id', $about->id)
                        ->first();

                    if ($photo) {
                        Log::info('Deleting photo:', ['id' => $photo->id, 'path' => $photo->photo_path]);

                        // Hapus file fisik
                        if (File::exists(public_path($photo->photo_path))) {
                            File::delete(public_path($photo->photo_path));
                            Log::info('Physical file deleted:', ['path' => $photo->photo_path]);
                        }

                        // Hapus record dari database
                        $photo->delete();
                        Log::info('Database record deleted for photo ID:', ['photo_id' => $photoId]);
                    } else {
                        Log::warning('Photo not found or not belongs to this about:', ['photo_id' => $photoId]);
                    }
                }
            }
        }

        // Handle foto baru
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('aboutus', 'public');
                    Aboutus_photo::create([
                        'aboutus_id' => $about->id,
                        'photo_path' => 'storage/' . $path,
                    ]);
                    Log::info('New photo added:', ['path' => $path]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $about = AboutUs::with('photos')->findOrFail($id);

        foreach ($about->photos as $photo) {
            if (File::exists(public_path($photo->photo_path))) {
                File::delete(public_path($photo->photo_path));
            }
            $photo->delete();
        }

        $about->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // Method untuk hapus foto individual (dari tabel utama)
    public function deletePhoto($id)
    {
        $photo = Aboutus_photo::findOrFail($id);

        if (File::exists(public_path($photo->photo_path))) {
            File::delete(public_path($photo->photo_path));
        }

        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus.']);
    }
}
