@extends('layouts.app')

@section('title', 'New Order - Laundry App')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Orders</div>
                <h2 class="page-title">Create New Order</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <form action="{{ route('orders.store') }}" method="POST" class="card">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <h3 class="card-title">Select Services</h3>
                        <div id="services-container">
                            <div class="row mb-3 service-row">
                                <div class="col-md-5">
                                    <label class="form-label required">Service</label>
                                    <select name="services[0][id]" class="form-select" required>
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                                {{ $service->name }} (Rp {{ number_format($service->price, 0) }} / {{ $service->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label required">Quantity/Weight</label>
                                    <input type="number" name="services[0][quantity]" class="form-control" required min="0.1" step="0.1" placeholder="e.g. 1.5">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-service-btn" disabled>Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="add-service-btn" class="btn btn-outline-primary">
                                + Add Another Service
                            </button>
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions?"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('orders.index') }}" class="btn btn-link link-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let serviceIndex = 1;
        const container = document.getElementById('services-container');
        const addBtn = document.getElementById('add-service-btn');

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row mb-3 service-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label required">Service</label>
                    <select name="services[${serviceIndex}][id]" class="form-select" required>
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->unit }}">
                                {{ $service->name }} (Rp {{ number_format($service->price, 0) }} / {{ $service->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label required">Quantity/Weight</label>
                    <input type="number" name="services[${serviceIndex}][quantity]" class="form-control" required min="0.1" step="0.1" placeholder="e.g. 1.5">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100 remove-service-btn">Remove</button>
                </div>
            `;
            container.appendChild(row);
            serviceIndex++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-service-btn')) {
                e.target.closest('.service-row').remove();
            }
        });
    });
</script>
@endsection
