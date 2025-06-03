<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter: from dan to (format: YYYY-MM-DD)
        $from = $request->input('from');
        $to   = $request->input('to');

        // Inisialisasi query
        $query = Transaction::with(['order', 'order.details.product'])
            ->orderBy('paid_at', 'desc');

        // Jika ada filter tanggal
        if ($from && $to) {
            try {
                $fromDate = Carbon::parse($from)->startOfDay();
                $toDate   = Carbon::parse($to)->endOfDay();
                $query->whereBetween('paid_at', [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Jika parsing gagal, abaikan filter
                // (Anda bisa menambahkan pesan error bila diinginkan)
            }
        }

        // Jalankan paginasi (10 item per halaman)
        $transactions = $query->paginate(10)->withQueryString();

        return view('transactions.index', compact('transactions', 'from', 'to'));
    }

    /**
     * Tampilkan detail satu transaksi lengkap.
     */
    public function show(Transaction $transaction)
    {
        // Pastikan relasi ter‐load
        $transaction->load(['order', 'order.details.product']);

        return view('transactions.show', compact('transaction'));
    }
}
