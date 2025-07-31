<?php

namespace App\Http\Controllers;

use App\Models\Sosmed;
use Illuminate\Http\Request;

class SosmedController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'link' => 'required|url|max:255',
        ]);

        Sosmed::updateOrCreate(
            ['nama' => $request->nama],
            ['link' => $request->link]
        );

        return back()->with('success', 'Akun sosial berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'link' => 'required|url|max:255',
        ]);

        $sosmed = Sosmed::findOrFail($id);
        $sosmed->update([
            'link' => $request->link,
        ]);

        return back()->with('success', 'Akun sosial berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sosmed = Sosmed::find($id);

        if (!$sosmed) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $sosmed->delete();

        return back()->with('success', 'Sosmed berhasil dihapus.');
    }
}
