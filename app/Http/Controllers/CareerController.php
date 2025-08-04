<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{

    public function index()
    {
        $careers = Career::latest()->get();
        return view('career.index', compact('careers'));
    }

    public function show($id)
    {
        $career = Career::findOrFail($id);
        return view('career.show', compact('career'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_type' => 'required|string',
            'position_title' => 'required|string',
            'lokasi' => 'required|string',
            'pengalaman' => 'required|string',
            'jam_kerja' => 'required|string',
            'hari_kerja' => 'required|string',
            'ringkasan' => 'required|string',
            'klasifikasi' => 'required|array',
            'klasifikasi.*' => 'required|string',
            'deskripsi' => 'required|array',
            'deskripsi.*' => 'required|string',
        ]);

        $career = new Career();
        $career->job_type = $request->job_type;
        $career->position_title = $request->position_title;
        $career->lokasi = $request->lokasi;
        $career->pengalaman = $request->pengalaman;
        $career->jam_kerja = $request->jam_kerja;
        $career->hari_kerja = $request->hari_kerja;
        $career->ringkasan = $request->ringkasan;
        $career->klasifikasi = $request->klasifikasi;
        $career->deskripsi = $request->deskripsi;
        $career->save();

        return redirect()->route('career.index')->with('success', 'Career created successfully.');
    }

    // PERBAIKAN: Gunakan $id sebagai parameter, bukan model binding
    public function update(Request $request, $id)
    {
        $request->validate([
            'job_type' => 'required|string',
            'position_title' => 'required|string',
            'lokasi' => 'required|string',
            'pengalaman' => 'required|string',
            'jam_kerja' => 'required|string',
            'hari_kerja' => 'required|string',
            'ringkasan' => 'required|string',
            'klasifikasi' => 'required|array',
            'klasifikasi.*' => 'required|string',
            'deskripsi' => 'required|array',
            'deskripsi.*' => 'required|string',
        ]);

        // PERBAIKAN: Cari dan update career berdasarkan ID
        $career = Career::findOrFail($id);
        $career->job_type = $request->job_type;
        $career->position_title = $request->position_title;
        $career->lokasi = $request->lokasi;
        $career->pengalaman = $request->pengalaman;
        $career->jam_kerja = $request->jam_kerja;
        $career->hari_kerja = $request->hari_kerja;
        $career->ringkasan = $request->ringkasan;
        $career->klasifikasi = $request->klasifikasi;
        $career->deskripsi = $request->deskripsi;
        $career->save();

        return redirect()->route('career.index')->with('success', 'Career updated successfully.');
    }

    public function destroy($id)
    {
        $career = Career::findOrFail($id);
        if ($career->image && Storage::disk('public')->exists($career->image)) {
            Storage::disk('public')->delete($career->image);
        }
        $career->delete();
        return redirect()->route('career.index')->with('success', 'Career deleted successfully.');
    }
}
