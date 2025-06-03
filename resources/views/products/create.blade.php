@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Produk Baru</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Menampilkan error validasi -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow mb-4">
    <div class="card-body">
      <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="category_id">Kategori</label>
          <select name="category_id" id="category_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}"
                      {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="name">Nama Produk</label>
          <input type="text" name="name" id="name"
                 class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
          <label for="price">Harga (Rp)</label>
          <input type="number" name="price" id="price"
                 class="form-control" value="{{ old('price') }}" min="0" step="100" required>
        </div>

        <div class="form-group">
          <label for="stock">Stok</label>
          <input type="number" name="stock" id="stock"
                 class="form-control" value="{{ old('stock', 0) }}" min="0" step="1" required>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>
@endsection
