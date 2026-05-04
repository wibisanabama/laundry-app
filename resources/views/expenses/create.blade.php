@extends('layouts.app')

@section('title', 'Record Expense - Sans Laundry')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Finance</div>
                <h2 class="page-title">Record Expense</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <form action="{{ route('expenses.store') }}" method="POST" class="card">
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
                        <div class="mb-3">
                            <label class="form-label required">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="e.g. Detergent, Electricity, Salary" value="{{ old('description') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required min="0" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('expenses.index') }}" class="btn btn-link link-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
