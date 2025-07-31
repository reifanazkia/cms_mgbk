<?php

namespace App\Http\Controllers;

use App\Models\CategoryKegiatan;
use Illuminate\Http\Request;

class CategoryKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = CategoryKegiatan::latest()->get();
        return view('category-kegiatan.index', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        CategoryKegiatan::create($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $kegiatan = CategoryKegiatan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kegiatan->update($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil ter update');
    }

    public function destroy($id)
    {
        $kegiatan = CategoryKegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
