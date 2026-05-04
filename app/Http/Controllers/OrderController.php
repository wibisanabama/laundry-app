<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $orders = Order::with('user')->latest()->get();
        } else {
            $orders = Order::where('user_id', auth()->id())->latest()->get();
        }
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $services = Service::all();
        return view('orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'services' => 'required|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:0.1',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => 0,
            'notes' => $request->notes,
        ]);

        $totalAmount = 0;

        foreach ($request->services as $serviceData) {
            $service = Service::find($serviceData['id']);
            $quantity = $serviceData['quantity'];
            $subtotal = $service->price * $quantity;
            $totalAmount += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => $quantity,
                'price' => $service->price,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load('items.service', 'user');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
