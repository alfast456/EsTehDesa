<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name')) – POS</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- SB Admin 2 (opsional) -->
  <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

  <style>
    body { background-color: #f4f6f9; }
    .outer-card { border-radius: 1rem; overflow: hidden; }
    .card-header-custom { background: linear-gradient(85deg, #4e73df 0%, #224abe 100%); color: #fff; }
    .product-card { cursor: pointer; transition: transform .2s, box-shadow .2s; border-radius: .75rem; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .product-img { height: 120px; object-fit: cover; border-top-left-radius: .75rem; border-top-right-radius: .75rem; }
    .card-body-custom { padding: 1rem; }
    .cart-card { border-radius: .75rem; }
    .btn-proses { background: #1cc88a; border-color: #17a673; }
    .btn-proses:hover { background: #17a673; }
    .cart-item-img { width: 50px; height: 50px; object-fit: cover; border-radius: .5rem; }
    .quantity-control .btn { padding: .25rem .5rem; }
    @media (max-width: 767.98px) {
      #productList { margin-bottom: 1.5rem; }
    }
  </style>
</head>
<body id="page-top" class="bg-light">

  <div class="container-fluid py-4">
    <!-- Outer Card -->
    <div class="card shadow-lg">
      <div class="card-header bg-white">
        <h2 class="h4 mb-0">Form Pemesanan</h2>
      </div>
      <div class="card-body">
        <form id="orderForm" action="{{ route('pemesanan.store') }}" method="POST">
          @csrf
          <div class="row">
            <!-- Produk List Card -->
            <div class="col-lg-8">
              <div class="card mb-4">
                <div class="card-header bg-light">
                  <h5 class="mb-0">Produk</h5>
                </div>
                <div class="card-body">
                  <div id="productList" class="row">
                    @forelse($products as $product)
                      <div class="col-6 col-sm-4 col-md-3 mb-3">
                        <div class="card product-card h-100" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-image="{{ $product->image && Storage::exists('public/'.$product->image) ? asset('storage/'.$product->image) : asset('images/no-image.png') }}">
                          <img src="{{ asset('storage/'.$product->image) ?? asset('images/no-image.png') }}" class="card-img-top product-img" alt="{{ $product->name }}">
                          <div class="card-body p-2 text-center">
                            <h6 class="small text-truncate">{{ $product->name }}</h6>
                            <p class="mb-1 text-success font-weight-bold small">Rp {{ number_format($product->price,0,',','.') }}</p>
                            <p class="mb-0 text-muted small">Stok: {{ $product->stock }}</p>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="col-12">
                        <div class="alert alert-warning text-center mb-0">Belum ada produk tersedia.</div>
                      </div>
                    @endforelse
                  </div>
                </div>
              </div>
            </div>

            <!-- Keranjang Card -->
            <div class="col-lg-4">
              <div class="card mb-4">
                <div class="card-header bg-light">
                  <h5 class="mb-0">Keranjang</h5>
                </div>
                <div class="card-body" style="max-height:60vh; overflow-y:auto;">
                  <div id="cartItems">
                    <p class="text-center text-muted mb-0">Keranjang kosong</p>
                  </div>
                </div>
                <div class="card-footer bg-white">
                  <div class="d-flex justify-content-between">
                    <span class="font-weight-bold">Total:</span>
                    <span id="grandTotalDisplay" class="font-weight-bold">Rp 0</span>
                  </div>
                </div>
              </div>
              <button type="submit" id="submitOrderBtn" class="btn btn-success btn-block mb-0" disabled>
                <i class="fas fa-shopping-cart mr-2"></i> Proses Pesanan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  @include('layouts.partials.footer')

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(function(){
      let cart = {};
      const $cartItems    = $('#cartItems'),
            $grandTotal   = $('#grandTotalDisplay'),
            $submitBtn    = $('#submitOrderBtn'),
            $form         = $('#orderForm');
      function renderCart(){
        $cartItems.empty(); let total=0;
        if(!Object.keys(cart).length){
          $cartItems.html('<p class="text-center text-muted mb-0">Keranjang kosong</p>');
          $submitBtn.prop('disabled',true); $grandTotal.text('Rp 0'); return;
        }
        $submitBtn.prop('disabled',false);
        $.each(cart,(pid,item)=>{
          const sub=item.price*item.qty; total+=sub;
          const row=$(`<div class="d-flex align-items-center mb-2" data-id="${pid}">
            <img src="${item.image}" class="cart-item-img rounded mr-2">
            <div class="flex-fill">
              <p class="mb-1 small text-truncate">${item.name}</p>
              <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary decrease-qty" ${item.qty<=1?'disabled':''}>–</button>
                <input type="text" readonly class="form-control form-control-sm mx-1 text-center" value="${item.qty}" style="width:40px">
                <button class="btn btn-sm btn-outline-secondary increase-qty" ${item.qty>=item.stock?'disabled':''}>+</button>
                <span class="ml-auto font-weight-bold small">Rp ${sub.toLocaleString('id-ID')}</span>
              </div>
            </div>
            <button class="btn btn-sm btn-danger ml-2 remove-item"><i class="fas fa-trash-alt"></i></button>
          </div>`);
          $cartItems.append(row);
        });
        $grandTotal.text('Rp '+total.toLocaleString('id-ID')); updateHidden();
      }
      function updateHidden(){ $form.find('input[name^="items"]').remove(); let i=0;
        $.each(cart,(_,item)=>{ $form.append(`<input type="hidden" name="items[${i}][product_id]" value="${item.id}">`);
          $form.append(`<input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`);
          i++; }); }
      $(document).on('click','.product-card',function(){ const d=$(this).data(),id=d.id;
        if(!cart[id]) cart[id]={...d,qty:0}; if(cart[id].qty<d.stock) cart[id].qty++; else return alert('Stok tidak cukup'); renderCart(); });
      $cartItems.on('click','.increase-qty',function(){ const pid=$(this).closest('[data-id]').data('id'); if(cart[pid].qty<cart[pid].stock) cart[pid].qty++; renderCart(); });
      $cartItems.on('click','.decrease-qty',function(){ const pid=$(this).closest('[data-id]').data('id'); if(cart[pid].qty>1) cart[pid].qty--; renderCart(); });
      $cartItems.on('click','.remove-item',function(){ const pid=$(this).closest('[data-id]').data('id'); delete cart[pid]; renderCart(); });
      $form.on('submit',function(){ if(!Object.keys(cart).length){ alert('Keranjang kosong!'); return false;} });
      renderCart();
    });
  </script>
</body>
</html>
