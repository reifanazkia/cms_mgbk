<?php

namespace App\Http\Controllers;

use App\Models\TentangkamiCategory;
use Illuminate\Http\Request;

class CategoryTentangkami extends Controller
{
    public function index()
    {
        $kegiatan = TentangkamiCategory::latest()->get();
        return view('category-tentangkami.index', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        TentangkamiCategory::create($request->only('nama'));

        return redirect()->back()->with('success', 'data berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $kegiatan = TentangkamiCategory::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kegiatan->update($request->only('nama'));

        return redirect()->back()->with('success', 'data berhasil ter update');
    }

    public function destroy($id)
    {
        $kegiatan = TentangkamiCategory::findOrFail($id);
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
