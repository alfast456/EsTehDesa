@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Kategori: {{ $category->name }}</h1>
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
      <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="name">Nama Kategori</label>
          <input type="text" name="name" id="name"
                 class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>

        <button type="submit" class="btn btn-success">
          <i class="fas fa-save"></i> Update
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>
@endsection
