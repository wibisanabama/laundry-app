@extends('layouts.app')

@section('title', 'Edit Service - Sans Laundry')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Services</div>
                <h2 class="page-title">Edit Service: {{ $service->name }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <form action="{{ route('services.update', $service) }}" method="POST" class="card">
                    @csrf
                    @method('PUT')
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
                            <label class="form-label required">Service Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control" value="{{ old('price', $service->price) }}" required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Unit</label>
                                <select name="unit" class="form-select" required>
                                    <option value="kg" {{ old('unit', $service->unit) == 'kg' ? 'selected' : '' }}>KG</option>
                                    <option value="piece" {{ old('unit', $service->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">Duration (Hours)</label>
                                <input type="number" name="duration_hours" class="form-control" value="{{ old('duration_hours', $service->duration_hours) }}" required min="1">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('services.index') }}" class="btn btn-link link-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
