<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // Contoh di DashboardController@index
    public function index()
    {
        // (Query seperti sebelumnya)
        $salesLast7Days = Order::selectRaw("DATE(created_at) as date, SUM(total_amount) as total")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ubah agar $row->date di‐parse ke Carbon
        $labels = $salesLast7Days->map(function ($row) {
            return Carbon::parse($row->date)->format('d M');
        })->toArray();

        $totals = $salesLast7Days->pluck('total')
            ->map(fn($v) => (float) $v)
            ->toArray();

        // Hitung ringkasan
        $todaySales    = Order::whereDate('created_at', now())->sum('total_amount');
        $totalProducts = Product::count();
        $totalOrders   = Order::count();

        // Top 5 produk terlaris
        $topProducts = Product::select('products.id', 'products.name')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->selectRaw('products.id, products.name, SUM(order_details.quantity) as total_sold')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todaySales',
            'totalProducts',
            'totalOrders',
            'labels',
            'totals',
            'topProducts'
        ));
    }
}
