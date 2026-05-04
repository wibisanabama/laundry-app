@extends('layouts.app')

@section('title', 'Dashboard - Laundry App')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard</h2>
            </div>
            @if(auth()->user()->role === 'customer')
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('orders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        New Order
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Orders</div>
                        </div>
                        <div class="h1 mb-3">{{ $totalOrders }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pending Orders</div>
                        </div>
                        <div class="h1 mb-3">{{ $pendingOrders }}</div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->role === 'admin')
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Revenue</div>
                        </div>
                        <div class="h1 mb-3">Rp {{ number_format($revenue, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No.</th>
                                    @if(auth()->user()->role === 'admin')
                                    <th>Customer</th>
                                    @endif
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td><span class="text-secondary">{{ $order->id }}</span></td>
                                    @if(auth()->user()->role === 'admin')
                                    <td>{{ $order->user->name }}</td>
                                    @endif
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-secondary-lt">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info-lt">Processing</span>
                                        @elseif($order->status == 'ready')
                                            <span class="badge bg-primary-lt">Ready</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success-lt">Completed</span>
                                        @else
                                            <span class="badge bg-danger-lt">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center text-secondary">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
