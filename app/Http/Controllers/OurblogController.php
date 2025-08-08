<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ourblog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OurblogController extends Controller
{

    public function index()
    {
        $ourblogs = Ourblog::with('category')->latest()->get();
        $categories = Category::all();
        return view('ourblogs.index', compact('ourblogs', 'categories'));
    }

    public function show($id)
    {
        $blog = Ourblog::with('category')->findOrFail($id);
        return view('ourblogs.show', compact('blog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pub_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'waktu_baca' => 'required|string|max:75'
        ]);

        $imagePath = $request->file('image')->store('ourblogs', 'public');

        $blog = Ourblog::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'pub_date' => $request->pub_date,
            'category_id' => $request->category_id,
            'waktu_baca' => $request->waktu_baca,
        ]);

        return redirect()->back()->with('success', 'data berhasil ter di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $blog = Ourblog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pub_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'waktu_baca' => 'required|string|max:75'
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $imagePath = $request->file('image')->store('ourblogs', 'public');
            $blog->image = $imagePath;
        }

        $blog->update([
            'title' => $request->title,
            'description' => $request->description,
            'pub_date' => $request->pub_date,
            'category_id' => $request->category_id,
            'waktu_baca' => $request->waktu_baca
        ]);

        $blog->save();

        return redirect()->back()->with('success', 'data berhasil ter di update');
    }

    public function destroy($id)
    {
        $blog = Ourblog::findOrFail($id);
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $ids = $request->input('ids');

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada Berita yang dipilih');
        }

        try {
            $products = Ourblog::whereIn('id', $ids)->get();

            foreach ($products as $product) {
                // Hapus gambar dari storage jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                // Hapus produk
                $product->delete();
            }

            return back()->with('success', 'Produk terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
