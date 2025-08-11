<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tentangkami;
use App\Models\TentangkamiCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class TentangkamiController extends Controller
{
    public function index()
    {
        try {
            $tentangkami = Tentangkami::with('category')->latest()->get();
            $categories = TentangkamiCategory::all();

            return view('tentangkami.index', compact('tentangkami', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@index: ' . $e->getMessage());

            // Return with empty collections to prevent undefined variable errors
            $tentangkami = collect();
            $categories = collect();

            return view('tentangkami.index', compact('tentangkami', 'categories'))
                ->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function create()
    {
        try {
            $categories = TentangkamiCategory::all();
            return view('tentangkami.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category_tentangkami_id' => 'required|exists:tentangkami_categories,id',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'display_on_home' => 'boolean',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    $path = $file->store('tentangkami', 'public');
                    $imagePath = 'storage/' . $path;
                }
            }

            Tentangkami::create([
                'title' => $request->title,
                'category_tentangkami_id' => $request->category_tentangkami_id,
                'description' => $request->description,
                'image' => $imagePath,
                'display_on_home' => $request->boolean('display_on_home'),
            ]);

            return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($id)
    {
        try {
            $tentangkami = Tentangkami::with('category')->findOrFail($id);
            return view('tentangkami.show', compact('tentangkami'));
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        try {
            $tentangkami = Tentangkami::findOrFail($id);
            $categories = TentangkamiCategory::all();
            return view('tentangkami.edit', compact('tentangkami', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request data:', $request->all());

            $tentangkami = Tentangkami::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'category_tentangkami_id' => 'required|exists:tentangkami_categories,id',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'display_on_home' => 'boolean',
            ]);

            $updateData = [
                'title' => $request->title,
                'category_tentangkami_id' => $request->category_tentangkami_id,
                'description' => $request->description,
                'display_on_home' => $request->boolean('display_on_home'),
            ];

            // Handle image update
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    // Delete old image if exists
                    if ($tentangkami->image && File::exists(public_path($tentangkami->image))) {
                        File::delete(public_path($tentangkami->image));
                        Log::info('Old image deleted:', ['path' => $tentangkami->image]);
                    }

                    // Store new image
                    $path = $file->store('tentangkami', 'public');
                    $updateData['image'] = 'storage/' . $path;
                    Log::info('New image uploaded:', ['path' => $path]);
                }
            }

            $tentangkami->update($updateData);

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            $tentangkami = Tentangkami::findOrFail($id);

            // Delete associated image
            if ($tentangkami->image && File::exists(public_path($tentangkami->image))) {
                File::delete(public_path($tentangkami->image));
                Log::info('Image deleted:', ['path' => $tentangkami->image]);
            }

            $tentangkami->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // API Methods for AJAX/JSON responses
    public function getByCategory($categoryId)
    {
        try {
            $request = request();
            $request->validate([
                'category_id' => 'sometimes|exists:tentangkami_categories,id'
            ]);

            // Use the parameter if provided, otherwise use from request
            $categoryId = $categoryId ?? $request->category_id;

            $tentangkami = Tentangkami::with('category')
                ->where('category_tentangkami_id', $categoryId)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tentangkami
            ]);
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@getByCategory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }

    public function getByCategoryName($categoryName)
    {
        try {
            $category = TentangkamiCategory::where('name', $categoryName)->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $tentangkami = Tentangkami::with('category')
                ->where('category_tentangkami_id', $category->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tentangkami
            ]);
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@getByCategoryName: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }

    public function getDisplayOnHome()
    {
        try {
            $tentangkami = Tentangkami::with('category')
                ->where('display_on_home', true)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tentangkami
            ]);
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@getDisplayOnHome: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }

    public function getCategories()
    {
        try {
            $categories = TentangkamiCategory::all();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@getCategories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil kategori.'
            ], 500);
        }
    }

    // Additional method for getting all data as JSON (for API)
    public function apiIndex()
    {
        try {
            $tentangkami = Tentangkami::with('category')->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $tentangkami
            ]);
        } catch (\Exception $e) {
            Log::error('Error in TentangkamiController@apiIndex: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }
}
