@extends('layouts.app')

@section('content')
<style>
  /* ---------- Custom untuk tampilan POS ---------- */
  .product-card {
    cursor: pointer;
    transition: transform 0.1s ease-in-out;
  }
  .product-card:hover {
    transform: scale(1.02);
  }
  .product-img {
    height: 120px;
    object-fit: cover;
  }
  .cart-item-img {
    height: 50px;
    width: 50px;
    object-fit: cover;
  }
  .btn-qty {
    padding: 0 8px;
  }
  @media (max-width: 767.98px) {
    /* Mobile: agar grid vertikal, cart di bawah */
    #productList {
      margin-bottom: 1.5rem;
    }
  }
</style>

<div class="container-fluid">
  <!-- Judul dan tombol kembali -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 text-gray-800">Buat Pesanan (POS)</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
      <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
  </div>

  <!-- Form utama (POST ke store) -->
  <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf

    <div class="row">
      <!-- 1) Daftar Produk (POS Grid) -->
      <div class="col-lg-8" id="productList">
        <div class="row">
          @foreach($products as $product)
            <div class="col-6 col-sm-4 col-md-3 mb-3">
              <div class="card product-card h-100" data-id="{{ $product->id }}"
                                     data-name="{{ $product->name }}"
                                     data-price="{{ $product->price }}"
                                     data-stock="{{ $product->stock }}"
                                     @if($product->image && Storage::exists('public/'.$product->image))
                                       data-image="{{ asset('storage/'.$product->image) }}"
                                     @else
                                       data-image="{{ asset('images/no-image.png') }}"
                                     @endif
                                     >
                @if($product->image && Storage::exists('public/'.$product->image))
                  <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                @else
                  <div class="d-flex align-items-center justify-content-center bg-light product-img">
                    <span class="text-muted">No Image</span>
                  </div>
                @endif
                <div class="card-body p-2">
                  <h6 class="card-title mb-1 text-truncate">{{ $product->name }}</h6>
                  <p class="mb-0 text-success small">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                  </p>
                  <p class="mb-0 text-muted small">Stok: {{ $product->stock }}</p>
                </div>
              </div>
            </div>
          @endforeach

          @if($products->isEmpty())
            <div class="col-12 text-center">
              <p class="text-muted">Belum ada produk tersedia.</p>
            </div>
          @endif
        </div>
      </div>

      <!-- 2) Keranjang (Cart Summary) -->
      <div class="col-lg-4">
        <div class="card shadow mb-3">
          <div class="card-header py-2">
            <h6 class="m-0 font-weight-bold text-primary">Keranjang</h6>
          </div>
          <div class="card-body" style="max-height: 60vh; overflow-y: auto;">
            <div id="cartItems">
              <!-- Isi dinamis via JavaScript -->
              <p class="text-center text-muted">Keranjang kosong</p>
            </div>
          </div>
          <div class="card-footer">
            <div class="d-flex justify-content-between">
              <strong>Total:</strong>
              <strong id="grandTotalDisplay">Rp 0</strong>
            </div>
          </div>
        </div>

        <!-- Tombol Submit -->
        <div class="text-center">
          <button type="submit" class="btn btn-success btn-block mb-3" id="submitOrderBtn" disabled>
            <i class="fas fa-shopping-cart"></i> Proses Pesanan
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- JQuery & Bootstrap sudah di‐include dalam layouts.app -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Struktur data keranjang: 
    // { product_id: { id, name, price, image, stock, qty } }
    let cart = {};

    const cartItemsEl = $('#cartItems');
    const grandTotalDisplay = $('#grandTotalDisplay');
    const submitOrderBtn = $('#submitOrderBtn');

    // Fungsi untuk mem‐render cart ke HTML
    function renderCart() {
      cartItemsEl.empty();
      let total = 0;
      const keys = Object.keys(cart);
      if (keys.length === 0) {
        cartItemsEl.html('<p class="text-center text-muted">Keranjang kosong</p>');
        submitOrderBtn.prop('disabled', true);
      } else {
        submitOrderBtn.prop('disabled', false);
        keys.forEach((pid, idx) => {
          const item = cart[pid];
          const sub = item.price * item.qty;
          total += sub;

          // Elemen keranjang per produk
          const itemHtml = `
            <div class="d-flex align-items-center mb-2" data-id="${item.id}">
              <img src="${item.image}" class="cart-item-img rounded mr-2" alt="${item.name}">
              <div class="flex-grow-1">
                <p class="mb-1 text-truncate">${item.name}</p>
                <div class="d-flex align-items-center">
                  <button class="btn btn-sm btn-secondary btn-qty decrease-qty" ${item.qty <= 1 ? 'disabled' : ''}>–</button>
                  <input type="text" class="form-control form-control-sm mx-1 text-center qty-input" 
                         value="${item.qty}" 
                         style="width: 40px;" readonly>
                  <button class="btn btn-sm btn-secondary btn-qty increase-qty" ${item.qty >= item.stock ? 'disabled' : ''}>+</button>
                  <span class="ml-auto font-weight-bold">
                    Rp ${sub.toLocaleString('id-ID')}
                  </span>
                </div>
                <small class="text-muted">Harga: Rp ${item.price.toLocaleString('id-ID')}</small>
              </div>
              <button class="btn btn-sm btn-danger ml-2 remove-item">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          `;
          cartItemsEl.append(itemHtml);
        });

        grandTotalDisplay.text('Rp ' + total.toLocaleString('id-ID'));
      }

      // Update hidden inputs di dalam form untuk dikirimkan
      updateHiddenInputs();
    }

    // Tambah / Update hidden inputs berdasarkan cart
    function updateHiddenInputs() {
      // Hapus semuanya dulu
      $('input[name^="items"]').remove();

      let idx = 0;
      Object.values(cart).forEach(item => {
        // input items[idx][product_id]
        const inpId = `<input type="hidden" 
                             name="items[${idx}][product_id]" 
                             value="${item.id}">`;
        // input items[idx][quantity]
        const inpQty = `<input type="hidden" 
                              name="items[${idx}][quantity]" 
                              value="${item.qty}">`;
        $('#orderForm').append(inpId).append(inpQty);
        idx++;
      });
    }

    // Ketika produk di‐klik, tambahkan ke cart (atau jika sudah ada, +1)
    $(document).on('click', '.product-card', function() {
      const pid   = $(this).data('id').toString();
      const name  = $(this).data('name');
      const price = parseFloat($(this).data('price'));
      const stock = parseInt($(this).data('stock'));
      const image = $(this).data('image');

      if (!cart[pid]) {
        if (stock < 1) {
          alert('Stok habis!');
          return;
        }
        cart[pid] = {
          id: pid,
          name: name,
          price: price,
          stock: stock,
          image: image,
          qty: 1
        };
      } else {
        // Jika sudah ada, tambah selama tidak melebihi stok
        if (cart[pid].qty < stock) {
          cart[pid].qty++;
        } else {
          alert('Jumlah melebihi stok tersedia.');
        }
      }
      renderCart();
    });

    // Tombol “+” pada cart untuk tambah qty
    $(document).on('click', '.increase-qty', function(e) {
      e.stopPropagation();
      const pid = $(this).closest('[data-id]').data('id').toString();
      if (cart[pid].qty < cart[pid].stock) {
        cart[pid].qty++;
        renderCart();
      }
    });

    // Tombol “–” pada cart untuk kurangi qty
    $(document).on('click', '.decrease-qty', function(e) {
      e.stopPropagation();
      const pid = $(this).closest('[data-id]').data('id').toString();
      if (cart[pid].qty > 1) {
        cart[pid].qty--;
      }
      renderCart();
    });

    // Tombol hapus item dari cart
    $(document).on('click', '.remove-item', function(e) {
      e.stopPropagation();
      const pid = $(this).closest('[data-id]').data('id').toString();
      delete cart[pid];
      renderCart();
    });

    // Saat form submit, kita pastikan hidden inputs sudah terkini
    $('#orderForm').on('submit', function() {
      updateHiddenInputs();
      if (Object.keys(cart).length === 0) {
        alert('Keranjang masih kosong!');
        return false;
      }
    });

    // Inisialisasi awal
    renderCart();
  });
</script>
@endsection
