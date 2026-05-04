@extends('layouts.app')

@section('title', 'Services - Laundry App')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Management</div>
                <h2 class="page-title">Services</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                @if(auth()->user()->role === 'admin')
                <div class="btn-list">
                    <a href="{{ route('services.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        New Service
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $index => $service)
                        <tr>
                            <td><span class="text-secondary">{{ $index + 1 }}</span></td>
                            <td>{{ $service->name }}</td>
                            <td class="text-secondary text-truncate" style="max-width: 200px;">{{ $service->description }}</td>
                            <td>{{ strtoupper($service->unit) }}</td>
                            <td>Rp {{ number_format($service->price, 2) }}</td>
                            <td class="text-end">
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary">No services found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
