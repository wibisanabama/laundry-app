<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')->latest('date')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function destroy(Expense $expense)
    {
        if (auth()->user()->role !== 'admin' && $expense->user_id !== auth()->id()) {
            abort(403);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
