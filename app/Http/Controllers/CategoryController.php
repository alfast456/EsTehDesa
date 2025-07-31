<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Tampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'user_id' => 'required|exists:users,id',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update data kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            // Unique:abaikan kategori yang sedang diedit ($category->id)
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'user_id' => 'nullable|exists:users,id', // Pastikan user_id valid
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Pastikan tidak ada produk yang bergantung ke kategori ini (opsional)
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Tidak dapat menghapus kategori karena terdapat produk yang terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
