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
        $userId = auth()->id();

        $todaySales = Order::where('user_id', $userId)
            ->whereDate('created_at', now())
            ->sum('total_amount');
        $totalProducts = Product::where('user_id', $userId)->count();
        $lowStockThreshold = 5; // misalnya
        $lowStockCount = Product::where('user_id', $userId)
            ->where('stock', '<=', $lowStockThreshold)
            ->count();
        $totalOrders = Order::where('user_id', $userId)->count();

        // Grafik 7 hari terakhir
        $salesLast7Days = Order::selectRaw("DATE(created_at) as date, SUM(total_amount) as total")
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $labels = $salesLast7Days->map(fn($row) => \Carbon\Carbon::parse($row->date)->format('d M'))->toArray();
        $totals = $salesLast7Days->pluck('total')->map(fn($v) => (float)$v)->toArray();

        // Ringkasan Bulanan
        $monthSales = Order::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
        $daysInMonthSoFar = now()->day;
        $avgDailySales = $daysInMonthSoFar > 0
            ? round($monthSales / $daysInMonthSoFar)
            : 0;

        // Top 5 Produk Terlaris
        $topProducts = Product::select('products.id', 'products.name')
            ->where('products.user_id', $userId)
            ->join('order_details', 'products.id', 'order_details.product_id')
            ->join('orders', function ($join) use ($userId) {
                $join->on('orders.id', '=', 'order_details.order_id')
                    ->where('orders.user_id', $userId);
            })
            ->selectRaw('products.name, SUM(order_details.quantity) as total_sold')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 5 Pesanan Terbaru
        $recentOrders = Order::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todaySales',
            'totalProducts',
            'lowStockThreshold',
            'lowStockCount',
            'totalOrders',
            'labels',
            'totals',
            'monthSales',
            'avgDailySales',
            'topProducts',
            'recentOrders'
        ));
    }
}
