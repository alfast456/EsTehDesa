@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Kategori Baru</h1>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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
      <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="name">Nama Kategori</label>
          <input type="text" name="name" id="name"
                 class="form-control" value="{{ old('name') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>
@endsection
