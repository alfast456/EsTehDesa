<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function create()
    {
        // Ambil semua produk (nama, harga, stok) untuk dropdown
        $products = Product::select(['id', 'name', 'price', 'stock', 'image'])->orderBy('name')->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Simpan pemesanan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi minimal: items wajib, qty > 0
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        // Hitung total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $prod = Product::find($item['product_id']);
            $line = $prod->price * $item['quantity'];
            $totalAmount += $line;
        }

        // Simpan Order header
        $order = Order::create([
            'user_id'    => auth()->id(), // atau sesuai kebutuhan
            'total_amount' => $totalAmount,
            'status'       => 'pending',   // status awal
            // Anda bisa menambahkan kolom lain misalnya user_id, etc.
        ]);

        // Simpan OrderDetail
        foreach ($request->items as $item) {
            $prod = Product::find($item['product_id']);

            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $prod->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $prod->price,
                'sub_total'  => $prod->price * $item['quantity'],
            ]);

            // Kurangi stok produk jika perlu:
            $prod->decrement('stock', $item['quantity']);
        }

        // Buat entri Transaction (tanpa QRIS otomatis)
        Transaction::create([
            'order_id'       => $order->id,
            'payment_status' => 'unpaid',
            // 'qr_code_data'  => null,  // kita tidak butuh QRIS otomatis
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order berhasil dibuat. Silakan lakukan pembayaran secara manual menggunakan QRIS.');
    }


    /**
     * Tampilkan detail order dan tombol pembayaran/simulasi.
     */
    public function show(Order $order)
    {
        $order->load(['details.product', 'transaction']);

        return view('orders.show', compact('order'));
    }

    /**
     * Simulasi callback bayar: ubah status jadi 'paid' dan simpan paid_at.
     */
    public function pay(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Order sudah dibayar atau dibatalkan.');
        }

        // Update status Order
        $order->status = 'paid';
        $order->save();

        // Update Transaction
        $trx = $order->transaction;
        $trx->payment_status = 'paid';
        $trx->paid_at        = now();
        $trx->save();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pembayaran berhasil');
    }

    /**
     * Render view khusus struk (tanpa layout penuh), siap cetak.
     */
    public function print(Order $order)
    {
        $order->load(['details.product', 'transaction']);

        return view('orders.print', compact('order'));
    }
}
