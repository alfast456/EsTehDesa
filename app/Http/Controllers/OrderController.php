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
        // Validasi minimal: items harus array dengan product_id dan quantity
        $validated = $request->validate([
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

        // Pastikan stock cukup untuk setiap item (opsional tapi disarankan)
        foreach ($validated['items'] as $item) {
            $p = Product::find($item['product_id']);
            if ($p->stock < $item['quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors("Stok produk “{$p->name}” (ID: {$p->id}) tidak mencukupi.");
            }
        }

        // Transaksi DB agar konsisten
        DB::beginTransaction();
        try {
            // 1. Buat header Order
            $order = new Order;
            $order->user_id       = auth()->id();
            $order->total_amount  = 0;       // sementara, akan diupdate setelah hitung
            $order->status        = 'pending';
            $order->payment_method = 'QRIS';
            $order->qr_code_data  = '';      // akan diisi setelah hitung total
            $order->save();

            $total = 0;
            // 2. Simpan setiap OrderDetail dan kurangi stok
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $sub = $product->price * $item['quantity'];

                $detail = new OrderDetail;
                $detail->order_id   = $order->id;
                $detail->product_id = $product->id;
                $detail->quantity   = $item['quantity'];
                $detail->unit_price = $product->price;
                $detail->sub_total  = $sub;
                $detail->save();

                // Kurangi stok
                $product->decrement('stock', $item['quantity']);

                $total += $sub;
            }

            // 3. Update total_amount & generate payload QRIS (JSON)
            $order->total_amount = $total;
            $payload = [
                'order_id' => $order->id,
                'amount'   => $total,
                'timestamp' => Carbon::now()->toIso8601String(),
            ];
            $order->qr_code_data = json_encode($payload);
            $order->save();

            // 4. Buat entri Transaction dengan status ‘unpaid’
            Transaction::create([
                'order_id'       => $order->id,
                'payment_status' => 'unpaid',
                // paid_at tetap null
            ]);

            DB::commit();
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order berhasil dibuat. Silakan lakukan pembayaran (simulasi) menggunakan QRIS.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
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
            ->with('success', 'Pembayaran berhasil (simulasi).');
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
