@extends('layouts.app')

@section('title', 'Invoice - Sans Laundry')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Transactions</div>
                <h2 class="page-title">Invoice #{{ $order->id }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="window.print();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    Print Receipt
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
                        <p class="h3">Sans Laundry</p>
                        <address>
                            Jl. Bersih Selalu No. 1<br>
                            Jakarta, Indonesia<br>
                            0812-3456-7890
                        </address>
                    </div>
                    <div class="col-6 text-end">
                        <p class="h3">Customer</p>
                        <address>
                            <strong>{{ $order->customer->name }}</strong><br>
                            {{ $order->customer->address ?? '-' }}<br>
                            {{ $order->customer->phone ?? '-' }}
                        </address>
                    </div>
                    <div class="col-12 my-3">
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Invoice No:</strong><br> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="col-md-3">
                                <strong>Date:</strong><br> {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Est. Completion:</strong><br> 
                                {{ $order->estimated_completion_date ? $order->estimated_completion_date->format('d M Y, H:i') : '-' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Cashier:</strong><br> {{ $order->user->name }}
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <table class="table table-transparent table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 1%">No</th>
                            <th>Service</th>
                            <th class="text-center" style="width: 1%">Unit Price</th>
                            <th class="text-center" style="width: 1%">Qty/Weight</th>
                            <th class="text-end" style="width: 1%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotalSum = 0; @endphp
                        @foreach($order->items as $index => $item)
                        @php $subtotalSum += $item->subtotal; @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <p class="strong mb-1">{{ $item->service->name }}</p>
                            </td>
                            <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        
                        <tr>
                            <td colspan="4" class="strong text-end">Subtotal</td>
                            <td class="text-end">Rp {{ number_format($subtotalSum, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td colspan="4" class="strong text-end">Discount</td>
                            <td class="text-end text-danger">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="strong text-end text-uppercase"><strong>Grand Total</strong></td>
                            <td class="text-end fw-bold h3 mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        @if($order->notes)
                        <p class="text-secondary"><strong>Notes:</strong><br> {{ $order->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="mb-2">
                            Order Status: 
                            <span class="badge bg-primary">{{ strtoupper($order->status) }}</span>
                        </div>
                        <div>
                            Payment: 
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">PAID ({{ strtoupper($order->payment_method) }})</span>
                            @else
                                <span class="badge bg-danger">UNPAID</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <p class="text-secondary text-center mt-5">Thank you for trusting our service!</p>
            </div>
        </div>

        <div class="card mt-3 d-print-none">
            <div class="card-header">
                <h3 class="card-title">Update Order & Payment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update', $order) }}" method="POST" class="row gx-3 align-items-end">
                    @csrf
                    @method('PUT')
                    <div class="col-md-3">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status_update" class="form-select">
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="payment_method_update_container" style="{{ $order->payment_status == 'paid' ? '' : 'display: none;' }}">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash" {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ $order->payment_method == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="qris" {{ $order->payment_method == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('payment_status_update').addEventListener('change', function() {
        if (this.value === 'paid') {
            document.getElementById('payment_method_update_container').style.display = 'block';
        } else {
            document.getElementById('payment_method_update_container').style.display = 'none';
        }
    });
</script>
@endsection
