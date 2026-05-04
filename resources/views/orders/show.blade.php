@extends('layouts.app')

@section('title', 'Order Details - Laundry App')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Orders</div>
                <h2 class="page-title">Order #{{ $order->id }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="window.print();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success d-print-none">{{ session('success') }}</div>
        @endif
        
        <div class="card card-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="h3">Laundry App</p>
                        <address>
                            123 Wash Street<br>
                            Clean City, 12345<br>
                            hello@laundryapp.com
                        </address>
                    </div>
                    <div class="col-6 text-end">
                        <p class="h3">Client</p>
                        <address>
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->address ?? 'No Address' }}<br>
                            {{ $order->user->phone ?? 'No Phone' }}<br>
                            {{ $order->user->email }}
                        </address>
                    </div>
                    <div class="col-12 my-3">
                        <h1>Invoice #{{ $order->id }}</h1>
                        <div>Date: {{ $order->created_at->format('d M Y, H:i') }}</div>
                        <div>Status: 
                            <strong class="text-uppercase">
                                {{ $order->status }}
                            </strong>
                        </div>
                    </div>
                </div>
                <table class="table table-transparent table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%">No</th>
                            <th>Service</th>
                            <th class="text-center" style="width: 1%">Unit Price</th>
                            <th class="text-center" style="width: 1%">Quantity</th>
                            <th class="text-end" style="width: 1%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <p class="strong mb-1">{{ $item->service->name }}</p>
                            </td>
                            <td class="text-center">Rp {{ number_format($item->price, 0) }} / {{ $item->service->unit }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="strong text-end">Grand Total</td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0) }}</td>
                        </tr>
                    </tbody>
                </table>
                @if($order->notes)
                <p class="text-secondary mt-5"><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
                <p class="text-secondary text-center mt-5">Thank you for choosing our services!</p>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        <div class="card mt-3 d-print-none">
            <div class="card-header">
                <h3 class="card-title">Update Order Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update', $order) }}" method="POST" class="row gx-3 align-items-center">
                    @csrf
                    @method('PUT')
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
