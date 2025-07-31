<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer',
    ]);

    $ids = $request->input('ids');

    if (empty($ids)) {
        return back()->with('error', 'Tidak ada produk yang dipilih');
    }

    try {
        $products = Product::whereIn('id', $ids)->get();

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
