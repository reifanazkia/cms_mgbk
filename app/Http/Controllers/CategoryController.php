<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil di tambahkan');
    }

    public function update(Request $request, $id)
    {
        $categories = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categories->update($request->only('name'));

        return redirect()->back()->with('success', 'data berhasil ter update');
    }

    public function destroy($id)
    {
        $categories = Category::findOrFail($id);
        $categories->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
