@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul & tombol kembali -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Produk: {{ $product->name }}</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Validasi & Error -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-body">
          <form action="{{ route('products.update', $product->id) }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="category_id">Kategori</label>
              <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}"
                    {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="name">Nama Produk</label>
              <input type="text" name="name" id="name"
                     class="form-control"
                     value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
              <label for="price">Harga (Rp)</label>
              <input type="number" name="price" id="price"
                     class="form-control"
                     value="{{ old('price', $product->price) }}"
                     min="0" step="100" required>
            </div>

            <div class="form-group">
              <label for="stock">Stok</label>
              <input type="number" name="stock" id="stock"
                     class="form-control"
                     value="{{ old('stock', $product->stock) }}"
                     min="0" step="1" required>
            </div>

            <div class="form-group">
              <label>Gambar Saat Ini</label><br>
              @if($product->image && Storage::exists('public/' . $product->image))
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="img-fluid mb-3"
                     style="max-width: 200px;">
              @else
                <p class="text-muted">Belum ada gambar</p>
              @endif
            </div>

            <div class="form-group">
              <label for="image">Ganti Gambar Produk</label>
              <input type="file" name="image" id="image" class="form-control-file">
              <small class="form-text text-muted">
                Pilih file jika ingin mengubah gambar. Maks 2MB.
              </small>
            </div>

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
