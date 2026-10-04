@extends('layouts.app')

@section('content')
    <div class="col-12 col-sm-10 col-md-8 col-lg-4">
        <div class="text-center mb-4">
            <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center"
                 style="width: 64px; height: 64px;">
                <span class="fw-bold text-primary fs-4">POS</span>
            </div>
            <h1 class="h4 mt-3 mb-1">QuickPOS</h1>
            <p class="text-muted small mb-0">Sign in to manage products and inventory.</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus
                               placeholder="admin@example.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required placeholder="Enter your password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

