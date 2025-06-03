@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul Halaman dan Tombol Tambah -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Kategori</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Tambah Kategori
    </a>
  </div>

  <!-- Notifikasi -->
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <!-- Tabel Kategori -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Nama Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $index => $category)
              <tr>
                <td>{{ $categories->firstItem() + $index }}</td>
                <td>{{ $category->name }}</td>
                <td>
                  <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach

            @if($categories->isEmpty())
              <tr>
                <td colspan="3" class="text-center">Belum ada data kategori.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-end">
        {{ $categories->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
