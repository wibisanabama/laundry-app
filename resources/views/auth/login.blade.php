@extends('layouts.auth')

@section('title', 'Login - Sans Laundry')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Login</h2>
        <form action="{{ route('login') }}" method="POST" autocomplete="off" novalidate>
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
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" autocomplete="off" value="{{ old('email') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label">
                    Password
                </label>
                <div class="input-group input-group-flat">
                    <input type="password" name="password" class="form-control" placeholder="Your password" autocomplete="off" required>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
    </div>
</div>
@endsection
