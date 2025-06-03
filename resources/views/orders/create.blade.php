@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Judul dan tombol kembali -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Buat Pesanan Baru</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Validasi & Error -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow mb-4">
    <div class="card-body">
      <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
        @csrf

        <div class="table-responsive">
          <table class="table table-bordered" id="orderTable">
            <thead class="thead-light">
              <tr>
                <th style="width: 45%;">Produk</th>
                <th style="width: 20%;">Harga (Rp)</th>
                <th style="width: 15%;">Stok</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 10%;">Subtotal (Rp)</th>
                <th style="width: 10%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr class="order-row">
                <td>
                  <select name="items[0][product_id]" class="form-control product-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                      <option value="{{ $p->id }}"
                              data-price="{{ $p->price }}"
                              data-stock="{{ $p->stock }}">
                        {{ $p->name }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="text" name="items[0][price]" class="form-control price-input" value="0" readonly>
                </td>
                <td>
                  <input type="text" name="items[0][stock]" class="form-control stock-input" value="0" readonly>
                </td>
                <td>
                  <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required>
                </td>
                <td>
                  <input type="text" name="items[0][subtotal]" class="form-control subtotal-input" value="0" readonly>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-success add-row">
                    <i class="fas fa-plus"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-danger remove-row" style="display: none;">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">Total Keseluruhan:</th>
                <th>
                  <input type="text" id="grandTotal" class="form-control" value="0" readonly>
                </th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-shopping-cart"></i> Proses Pesanan
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>

<!-- JQuery & Bootstrap JS sudah diasumsikan sudah di-include di layouts.app -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  let rowIndex = 1; // untuk menghitung indeks items[]

  // Fungsi untuk menghitung subtotal pada satu baris
  function recalcRow($row) {
    const price = parseFloat($row.find('.price-input').val()) || 0;
    const qty   = parseInt($row.find('.qty-input').val()) || 0;
    const sub   = price * qty;
    $row.find('.subtotal-input').val(sub.toLocaleString('id-ID'));
    recalcGrandTotal();
  }

  // Hitung total keseluruhan
  function recalcGrandTotal() {
    let sum = 0;
    $('.subtotal-input').each(function() {
      // `this.value` adalah string format ribuan, hilangkan pemisah
      let val = $(this).val().replace(/\./g, '').replace(/,/g, '');
      sum += parseFloat(val) || 0;
    });
    $('#grandTotal').val(sum.toLocaleString('id-ID'));
  }

  // Saat user memilih produk di row tertentu
  $(document).on('change', '.product-select', function() {
    const $row = $(this).closest('tr');
    const price = parseFloat($(this).find(':selected').data('price')) || 0;
    const stock = parseInt($(this).find(':selected').data('stock')) || 0;

    // Set harga & stok di input readonly
    $row.find('.price-input').val(price);
    $row.find('.stock-input').val(stock);
    // Setelah pilih, hitung ulang subtotal
    recalcRow($row);
  });

  // Saat qty diubah
  $(document).on('input', '.qty-input', function() {
    const $row = $(this).closest('tr');
    let qty = parseInt($(this).val()) || 0;
    const stock = parseInt($row.find('.stock-input').val()) || 0;

    // Pastikan qty <= stock
    if (qty > stock) {
      alert('Jumlah tidak boleh melebihi stok tersedia (' + stock + ').');
      $(this).val(stock);
      qty = stock;
    }
    recalcRow($row);
  });

  // Tombol “Tambah Baris”
  $(document).on('click', '.add-row', function() {
    const $lastRow = $('#orderTable tbody tr.order-row:last');
    const $newRow = $lastRow.clone();

    // Reset nilai input di clone
    $newRow.find('select').val('');
    $newRow.find('.price-input, .stock-input, .subtotal-input').val('0');
    $newRow.find('.qty-input').val('1');

    // Sesuaikan nama atribut agar index unik
    $newRow.find('select').attr('name', `items[${rowIndex}][product_id]`);
    $newRow.find('.price-input').attr('name', `items[${rowIndex}][price]`);
    $newRow.find('.stock-input').attr('name', `items[${rowIndex}][stock]`);
    $newRow.find('.qty-input').attr('name', `items[${rowIndex}][quantity]`);
    $newRow.find('.subtotal-input').attr('name', `items[${rowIndex}][subtotal]`);

    // Tampilkan tombol “Hapus Baris” pada baris baru
    $newRow.find('.remove-row').show();

    // Sisipkan setelah baris terakhir
    $lastRow.after($newRow);
    rowIndex++;
  });

  // Tombol “Hapus Baris”
  $(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
    recalcGrandTotal();
  });

  // Saat form disubmit, hijack agar nilai subtotal dalam format numeric (hilangkan ribuan)
  $('#orderForm').on('submit', function() {
    $('.subtotal-input').each(function() {
      let raw = $(this).val().replace(/\./g, '').replace(/,/g, '');
      $(this).val(raw);
    });
    // Grand total tidak dikirim ke server, jadi tidak perlu diubah
  });
});
</script>
@endsection
