@extends('layouts.auth')

@section('title', 'Register - Laundry App')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="text-center mb-4">Create new account</h2>
        <form action="{{ route('register') }}" method="POST" autocomplete="off" novalidate>
            @csrf
            
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
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number (Optional)</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Address (Optional)</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Enter full address">{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group input-group-flat">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" autocomplete="off" required>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Create new account</button>
            </div>
        </form>
    </div>
</div>
<div class="text-center text-secondary mt-3">
    Already have account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
</div>
@endsection
