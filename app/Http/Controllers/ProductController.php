<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CategoryStore;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = CategoryStore::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Method baru untuk menampilkan produk berdasarkan kategori
    public function getByCategory($categoryId)
    {
        $category = CategoryStore::findOrFail($categoryId);
        $products = Product::where('category_store_id', $categoryId)
                          ->with('category')
                          ->get();

        return view('products.by-category', compact('products', 'category'));
    }

    // Method API untuk mendapatkan produk berdasarkan kategori (JSON response)
    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_store_id', $categoryId)
                          ->with('category')
                          ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'disusun' => 'required|string|max:255',
            'jumlah_modul' => 'required|integer|min:1',
            'bahasa' => 'required|string|max:100',
            'notlp' => 'nullable|string|max:20', // Field nomor telepon
            'category_store_id' => 'required|exists:store_categories,id', // Validasi kategori
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
            'disusun' => 'required|string|max:255',
            'jumlah_modul' => 'required|integer|min:1',
            'bahasa' => 'required|string|max:100',
            'notlp' => 'nullable|string|max:20', // Field nomor telepon
            'category_store_id' => 'required|exists:store_categories,id', // Validasi kategori
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
