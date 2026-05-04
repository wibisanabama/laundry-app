<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer', 'user')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::all();
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:0.1',
            'discount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_method' => 'nullable|in:cash,transfer,qris',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_status == 'paid' ? $request->payment_method : null,
            'discount' => $request->discount ?? 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'notes' => $request->notes,
        ]);

        $subtotalAmount = 0;
        $maxDuration = 0;

        foreach ($request->services as $serviceData) {
            $service = Service::find($serviceData['id']);
            $quantity = $serviceData['quantity'];
            $subtotal = $service->price * $quantity;
            $subtotalAmount += $subtotal;

            if ($service->duration_hours > $maxDuration) {
                $maxDuration = $service->duration_hours;
            }

            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => $quantity,
                'price' => $service->price,
                'subtotal' => $subtotal,
            ]);
        }

        $totalAmount = $subtotalAmount - ($request->discount ?? 0);
        
        $order->update([
            'total_amount' => $totalAmount,
            'paid_amount' => $request->payment_status == 'paid' ? $totalAmount : 0,
            'estimated_completion_date' => now()->addHours($maxDuration),
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Transaction saved successfully.');
    }

    public function show(Order $order)
    {
        $order->load('items.service', 'customer', 'user');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_method' => 'nullable|in:cash,transfer,qris',
        ]);

        $data = [
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_status == 'paid' ? $request->payment_method : $order->payment_method,
        ];

        if ($request->payment_status == 'paid') {
            $data['paid_amount'] = $order->total_amount;
        }

        $order->update($data);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
