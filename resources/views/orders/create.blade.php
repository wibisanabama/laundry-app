@extends('layouts.app')

@section('title', 'New Transaction - Sans Laundry')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Transactions</div>
                <h2 class="page-title">New POS Transaction</h2>
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
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Select Customer</label>
                                <select name="customer_id" class="form-select" required>
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Don't see the customer? <a href="{{ route('customers.create') }}">Add new customer first</a>.</small>
                            </div>
                        </div>

                        <hr>
                        <h3 class="card-title">Services</h3>
                        <div id="services-container">
                            <div class="row mb-3 service-row">
                                <div class="col-md-5">
                                    <label class="form-label required">Service</label>
                                    <select name="services[0][id]" class="form-select service-select" required>
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }} / {{ $service->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Qty / Weight</label>
                                    <input type="number" name="services[0][quantity]" class="form-control qty-input" required min="0.1" step="0.1" placeholder="e.g. 1.5">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" class="form-control subtotal-display" readonly value="Rp 0">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-service-btn" disabled>X</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="button" id="add-service-btn" class="btn btn-outline-primary">
                                + Add Service
                            </button>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Order Notes / Instructions</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="e.g. Don't use bleach, express delivery, etc."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Discount (Rp)</label>
                                    <input type="number" name="discount" id="discount-input" class="form-control" value="0" min="0" step="1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Payment Status</label>
                                    <select name="payment_status" id="payment_status" class="form-select" required>
                                        <option value="unpaid">Unpaid (Belum Lunas)</option>
                                        <option value="paid">Paid (Lunas)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="payment_method_container" style="display: none;">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Bank Transfer</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                                <div class="card bg-primary text-white mt-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 class="mb-0 text-white">Grand Total:</h3>
                                            <h2 class="mb-0 text-white" id="grand-total-display">Rp 0</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('orders.index') }}" class="btn btn-link link-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Process Transaction</button>
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
        const paymentStatusSelect = document.getElementById('payment_status');
        const paymentMethodContainer = document.getElementById('payment_method_container');
        const discountInput = document.getElementById('discount-input');
        const grandTotalDisplay = document.getElementById('grand-total-display');

        function calculateTotals() {
            let total = 0;
            const rows = document.querySelectorAll('.service-row');
            
            rows.forEach(row => {
                const select = row.querySelector('.service-select');
                const qtyInput = row.querySelector('.qty-input');
                const subDisplay = row.querySelector('.subtotal-display');
                
                let price = 0;
                if (select.options[select.selectedIndex]) {
                    price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
                }
                const qty = parseFloat(qtyInput.value) || 0;
                const subtotal = price * qty;
                
                subDisplay.value = 'Rp ' + subtotal.toLocaleString('id-ID');
                total += subtotal;
            });

            const discount = parseFloat(discountInput.value) || 0;
            const grandTotal = Math.max(0, total - discount);
            grandTotalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        container.addEventListener('change', calculateTotals);
        container.addEventListener('input', calculateTotals);
        discountInput.addEventListener('input', calculateTotals);

        paymentStatusSelect.addEventListener('change', function() {
            if (this.value === 'paid') {
                paymentMethodContainer.style.display = 'block';
            } else {
                paymentMethodContainer.style.display = 'none';
            }
        });

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row mb-3 service-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label required">Service</label>
                    <select name="services[${serviceIndex}][id]" class="form-select service-select" required>
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }} / {{ $service->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Qty / Weight</label>
                    <input type="number" name="services[${serviceIndex}][quantity]" class="form-control qty-input" required min="0.1" step="0.1" placeholder="e.g. 1.5">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control subtotal-display" readonly value="Rp 0">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100 remove-service-btn">X</button>
                </div>
            `;
            container.appendChild(row);
            serviceIndex++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-service-btn')) {
                e.target.closest('.service-row').remove();
                calculateTotals();
            }
        });
    });
</script>
@endsection
