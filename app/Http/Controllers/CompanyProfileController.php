<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $companyProfiles = Alamat::all();
        return view('setting.setting', compact('companyProfiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'lokasi' => 'required|string',
        ]);

        Alamat::create([
            'nama_tempat' => $request->nama_tempat,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('profile-setting')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $profile = Alamat::findOrFail($id);
        return view('setting.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'lokasi' => 'required|string',
        ]);

        $profile = Alamat::findOrFail($id);
        $profile->update([
            'nama_tempat' => $request->nama_tempat,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('profile-setting')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profile = Alamat::findOrFail($id);
        $profile->delete();

        return redirect()->route('profile-setting')->with('success', 'Alamat berhasil dihapus.');
    }
}
