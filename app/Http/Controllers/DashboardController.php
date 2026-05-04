<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->count();
        
        $revenue = Order::where('payment_status', 'paid')->sum('paid_amount');
        $expenses = Expense::sum('amount');
        $netProfit = $revenue - $expenses;
        
        $unpaidAmount = Order::where('payment_status', 'unpaid')->sum('total_amount');

        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        return view('dashboard.index', compact('totalOrders', 'pendingOrders', 'revenue', 'expenses', 'netProfit', 'unpaidAmount', 'recentOrders'));
    }
}
