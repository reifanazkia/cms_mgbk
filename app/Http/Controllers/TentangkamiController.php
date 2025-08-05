<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tentangkami;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class TentangkamiController extends Controller
{
    public function index()
    {
        $tentangkami = Tentangkami::latest()->get();
        return view('tentangkami.index', compact('tentangkami'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Visi,Misi,Sejarah',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $path = $file->store('tentangkami', 'public');
                $imagePath = 'storage/' . $path;
            }
        }

        Tentangkami::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());

        $tentangkami = Tentangkami::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Visi,Misi,Sejarah',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
        ];

        // Handle image update
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                // Delete old image if exists
                if ($tentangkami->image && File::exists(public_path($tentangkami->image))) {
                    File::delete(public_path($tentangkami->image));
                    Log::info('Old image deleted:', ['path' => $tentangkami->image]);
                }

                // Store new image
                $path = $file->store('tentangkami', 'public');
                $updateData['image'] = 'storage/' . $path;
                Log::info('New image uploaded:', ['path' => $path]);
            }
        }

        $tentangkami->update($updateData);

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tentangkami = Tentangkami::findOrFail($id);

        // Delete associated image
        if ($tentangkami->image && File::exists(public_path($tentangkami->image))) {
            File::delete(public_path($tentangkami->image));
            Log::info('Image deleted:', ['path' => $tentangkami->image]);
        }

        $tentangkami->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // Additional methods for specific categories
    public function getByCategory($category)
    {
        $request = request();
        $request->validate([
            'category' => 'required|in:Visi,Misi,Sejarah'
        ]);

        $tentangkami = Tentangkami::where('category', $category)->latest()->get();
        return response()->json($tentangkami);
    }

    public function getVisi()
    {
        $visi = Tentangkami::where('category', 'Visi')->latest()->get();
        return response()->json($visi);
    }

    public function getMisi()
    {
        $misi = Tentangkami::where('category', 'Misi')->latest()->get();
        return response()->json($misi);
    }

    public function getSejarah()
    {
        $sejarah = Tentangkami::where('category', 'Sejarah')->latest()->get();
        return response()->json($sejarah);
    }
}
