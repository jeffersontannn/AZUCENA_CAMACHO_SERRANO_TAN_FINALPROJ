<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayStart = now()->startOfDay()->toDateTimeString();
        $todayEnd = now()->endOfDay()->toDateTimeString();

        $todaySales = DB::table('sales')
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->selectRaw('COALESCE(SUM(total_price), 0) as total, COALESCE(SUM(quantity), 0) as qty')
            ->first();

        $totalTx = DB::table('sales')
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->count();

        $lowStock = Product::where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'ASC')
            ->limit(5)
            ->get();

        $recentSales = Sale::history()->take(10);

        return view('dashboard', [
            'todaySales' => $todaySales->total ?? 0,
            'totalTx' => $totalTx,
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'lowStock' => $lowStock,
            'recentSales' => $recentSales,
        ]);
    }
}
