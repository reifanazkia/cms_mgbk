<?php

namespace App\Http\Controllers;

use App\Models\Hows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HowsController extends Controller
{

    public function index()
    {
        $steps = Hows::orderBy('step_number')->get();
        return view('hows.index', compact('steps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('kta', 'public');
        }

        Hows::create($data);
        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $how = Hows::findOrFail($id);

        $data = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($how->image && Storage::disk('public')->exists($how->image)) {
                Storage::disk('public')->delete($how->image);
            }
            $data['image'] = $request->file('image')->store('kta', 'public');
        }

        $how->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function show($id)
    {
        $how = Hows::findOrFail($id);
        return view('hows.show', compact('how'));
    }

    public function destroy($id)
    {
        $how = Hows::findOrFail($id);

        if ($how->image && Storage::disk('public')->exists($how->image)) {
            Storage::disk('public')->delete($how->image);
        }

        $how->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
