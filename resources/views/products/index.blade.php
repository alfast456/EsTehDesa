@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul dan tombol tambah -->
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

  <!-- Tabel Produk dengan DataTable -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table id="productsTable" class="table table-bordered table-hover" width="100%">
          <thead class="thead-light">
            <tr>
              <th>No</th>
              <th>Gambar</th>
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
                <td class="text-center">
                  @if($product->image && Storage::exists('public/' . $product->image))
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="img-thumbnail"
                         style="max-width: 60px; max-height: 60px;">
                  @else
                    <span class="text-muted">No Image</span>
                  @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                  <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning mb-1">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('products.destroy', $product->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger mb-1">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach

            @if($products->isEmpty())
              <tr>
                <td colspan="7" class="text-center">Belum ada data produk.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Pagination (hanya untuk mobile, DataTable handle desktop) -->
      <div class="d-flex justify-content-end d-block d-sm-none">
        {{ $products->links() }}
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
      $('#productsTable').DataTable({
        paging:   false,      // matikan paging karena Laravel pagination untuk mobile
        searching: true,
        ordering: true,
        info:     false,      // sembunyikan info default DataTables
        responsive: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        },
        columnDefs: [
          { orderable: false, targets: [1, 6] } // non-urutable untuk kolom Gambar & Aksi
        ]
      });
    });
  </script>
@endpush
