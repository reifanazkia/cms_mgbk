<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\CategoryAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::with('category')->latest()->get();
        $categories = CategoryAnggota::all();

        return view('anggota.index', compact('anggotas', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_anggota_id' => 'required|exists:anggota_categories,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'facebook_id' => 'nullable|string|max:255',
            'instagram_id' => 'nullable|string|max:255',
            'tiktok_id' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('image')->store('anggota', 'public');

        Anggota::create([
            'category_anggota_id' => $request->category_anggota_id,
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'facebook_id' => $request->facebook_id,
            'instagram_id' => $request->instagram_id,
            'tiktok_id' => $request->tiktok_id,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'category_anggota_id' => 'required|exists:anggota_categories,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'facebook_id' => 'nullable|string|max:255',
            'instagram_id' => 'nullable|string|max:255',
            'tiktok_id' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($anggota->image && Storage::disk('public')->exists($anggota->image)) {
                Storage::disk('public')->delete($anggota->image);
            }
            $imagePath = $request->file('image')->store('anggota', 'public');
            $anggota->image = $imagePath;
        }

        $anggota->update([
            'category_anggota_id' => $request->category_anggota_id,
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'facebook_id' => $request->facebook_id,
            'instagram_id' => $request->instagram_id,
            'tiktok_id' => $request->tiktok_id,
        ]);

        return redirect()->back()->with('success', 'Anggota berhasil diupdate');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->image && Storage::disk('public')->exists($anggota->image)) {
            Storage::disk('public')->delete($anggota->image);
        }

        $anggota->delete();

        return response()->json(['message' => 'Anggota berhasil dihapus']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $ids = $request->ids;

        try {
            $anggotas = Anggota::whereIn('id', $ids)->get();

            foreach ($anggotas as $anggota) {
                if ($anggota->image && Storage::disk('public')->exists($anggota->image)) {
                    Storage::disk('public')->delete($anggota->image);
                }
                $anggota->delete();
            }

            return back()->with('success', 'Anggota terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }
}
