<?php

namespace App\Http\Controllers;

use App\Models\Hows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HowsController extends Controller
{
    public function index()
    {
        $steps = Hows::orderBy('step_number')->get();
        return view('hows.index', compact('steps'));
    }

    public function store(Request $request)
    {
        // Custom validation
        $validator = Validator::make($request->all(), [
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'step_number.required' => 'Nomor step harus diisi',
            'step_number.integer' => 'Nomor step harus berupa angka',
            'step_number.min' => 'Nomor step minimal 1',
            'title.required' => 'Judul harus diisi',
            'title.max' => 'Judul maksimal 255 karakter',
            'description.required' => 'Deskripsi harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam validasi data');
        }

        try {
            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store file
                $data['image'] = $image->storeAs('kta', $filename, 'public');
            }

            // Create new record
            Hows::create($data);

            return redirect()->back()->with('success', 'Data KTA berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $how = Hows::findOrFail($id);

            // Custom validation
            $validator = Validator::make($request->all(), [
                'step_number' => 'required|integer|min:1',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], [
                'step_number.required' => 'Nomor step harus diisi',
                'step_number.integer' => 'Nomor step harus berupa angka',
                'step_number.min' => 'Nomor step minimal 1',
                'title.required' => 'Judul harus diisi',
                'title.max' => 'Judul maksimal 255 karakter',
                'description.required' => 'Deskripsi harus diisi',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
                'image.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan dalam validasi data');
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($how->image && Storage::disk('public')->exists($how->image)) {
                    Storage::disk('public')->delete($how->image);
                }

                $image = $request->file('image');

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store new file
                $data['image'] = $image->storeAs('kta', $filename, 'public');
            }

            // Update record
            $how->update($data);

            return redirect()->back()->with('success', 'Data KTA berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $how = Hows::findOrFail($id);
            return view('hows.show', compact('how'));
        } catch (\Exception $e) {
            return redirect()->route('hows.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function destroy($id)
    {
        try {
            $how = Hows::findOrFail($id);

            // Delete image file if exists
            if ($how->image && Storage::disk('public')->exists($how->image)) {
                Storage::disk('public')->delete($how->image);
            }

            // Delete record
            $how->delete();

            return redirect()->back()->with('success', 'Data KTA berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
