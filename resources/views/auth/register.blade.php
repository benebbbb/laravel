@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="auth-box">
    <div class="auth-box-header">
        <i class="bi bi-person-plus"></i>
        <h5>Create Account</h5>
        <div style="font-size:13px;opacity:.8;margin-top:4px">Register to get started</div>
    </div>
    <div class="auth-box-body">
        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Full Name</label>
                <input type="text" name="name" class="form-control form-control-sm"
                       value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Email</label>
                <input type="email" name="email" class="form-control form-control-sm"
                       value="{{ old('email') }}" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Password</label>
                <input type="password" name="password" class="form-control form-control-sm"
                       placeholder="Min. 8 characters" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-sm"
                       placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <hr class="my-3">
        <p class="text-center mb-0" style="font-size:13px">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </p>
    </div>
</div>
@endsection
