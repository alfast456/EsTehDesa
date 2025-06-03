@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul Halaman dan Tombol Tambah -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Tambah Produk
    </a>
  </div>

  <!-- Notifikasi -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Tabel Produk -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Harga (Rp)</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $index => $product)
              <tr>
                <td>{{ $products->firstItem() + $index }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                  <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach

            @if($products->isEmpty())
              <tr>
                <td colspan="6" class="text-center">Belum ada data produk.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-end">
        {{ $products->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
