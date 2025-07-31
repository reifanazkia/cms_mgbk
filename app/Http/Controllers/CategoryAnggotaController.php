<?php

namespace App\Http\Controllers;

use App\Models\CategoryAnggota;
use Illuminate\Http\Request;

class CategoryAnggotaController extends Controller
{
    public function index()
    {
        $anggota = CategoryAnggota::latest()->get();
        return view('category-anggota.index', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        CategoryAnggota::create($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $anggota = CategoryAnggota::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $anggota->update($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil ter update');
    }

    public function destroy($id)
    {
        $anggota = CategoryAnggota::findOrFail($id);
        $anggota->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
