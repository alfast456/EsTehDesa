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


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('user','pemesanan','detailpemesanan');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    public function user()
    {
        return view('user');
    }

    public function pemesanan()
    {
        // Ambil semua produk (nama, harga, stok) untuk dropdown
        $products = Product::select(['id', 'name', 'price', 'stock', 'image'])
            // ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        return view('pemesanan', compact('products'));
    }

    public function detailpemesanan(Order $order)
    {
        $order->load(['details.product', 'transaction']);

        return view('detailpemesanan', compact('order'));
    }
}
