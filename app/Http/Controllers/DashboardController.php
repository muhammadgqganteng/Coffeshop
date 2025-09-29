<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return $this->adminDashboard();
        }

        return redirect()->route('products.index');
    }

    private function adminDashboard()
    {
        $totalProducts = Cache::remember('total_products', 3600, function () {
            return Product::count();
        });

        $totalOrders = Cache::remember('total_orders', 3600, function () {
            return Order::count();
        });

        $totalUsers = Cache::remember('total_users', 3600, function () {
            return User::where('role', 'user')->count();
        });

        $totalRevenue = Cache::remember('total_revenue', 3600, function () {
            return Order::whereIn('status', ['confirmed', 'delivered'])->sum('total');
        });

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalOrders', 'totalUsers', 'totalRevenue', 'recentOrders'));
    }
}
