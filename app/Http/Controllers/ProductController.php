<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil data produk beserta relasi category, 10 per halaman
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Tampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        // Data kategori untuk dropdown
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        // Buat produk baru
        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        // Data kategori untuk dropdown
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        // Update produk
        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
