<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $orders = Order::whereMonth('created_at', $month)
                       ->whereYear('created_at', $year)
                       ->get();

        $expenses = Expense::whereMonth('date', $month)
                           ->whereYear('date', $year)
                           ->get();

        $totalRevenue = $orders->where('payment_status', 'paid')->sum('paid_amount');
        $totalUnpaid = $orders->where('payment_status', 'unpaid')->sum('total_amount');
        $totalExpense = $expenses->sum('amount');
        
        $netProfit = $totalRevenue - $totalExpense;

        return view('reports.index', compact('orders', 'expenses', 'totalRevenue', 'totalUnpaid', 'totalExpense', 'netProfit', 'month', 'year'));
    }
}
