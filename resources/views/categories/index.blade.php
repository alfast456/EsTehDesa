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

  <!-- Tabel Kategori dengan DataTable -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive d-none d-sm-block">
        <table id="categoriesTable" class="table table-bordered table-hover" width="100%">
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
                  <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning mb-1">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger mb-1">
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

      <!-- List kartu (untuk mobile) -->
      <div class="d-block d-sm-none">
        @foreach($categories as $index => $category)
          <div class="card mb-2 shadow-sm">
            <div class="card-body p-2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <p class="mb-1"><strong>#{{ $categories->firstItem() + $index }}</strong></p>
                  <p class="mb-1 small">{{ $category->name }}</p>
                </div>
                <div class="text-right">
                  <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning mb-1">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger mb-1">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Pagination Laravel (untuk mobile) -->
        <div class="d-flex justify-content-end">
          {{ $categories->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#categoriesTable').DataTable({
        paging:   false,      // gunakan pagination Laravel untuk mobile
        searching: true,
        ordering:  true,
        info:      false,     // sembunyikan info default DataTables
        responsive: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        },
        columnDefs: [
          { orderable: false, targets: 2 } // non-urutable kolom Aksi
        ]
      });
    });
  </script>
@endpush
