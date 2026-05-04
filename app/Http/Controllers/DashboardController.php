<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $revenue = Order::where('status', 'completed')->sum('total_amount');
            $recentOrders = Order::with('user')->latest()->take(5)->get();
        } else {
            $totalOrders = Order::where('user_id', $user->id)->count();
            $pendingOrders = Order::where('user_id', $user->id)->whereIn('status', ['pending', 'processing'])->count();
            $revenue = 0; // Not applicable for customer dashboard
            $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        }

        return view('dashboard.index', compact('totalOrders', 'pendingOrders', 'revenue', 'recentOrders'));
    }
}
