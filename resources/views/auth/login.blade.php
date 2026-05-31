@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="auth-box">
    <div class="auth-box-header">
        <i class="bi bi-capsule-pill"></i>
        <h5>MediTrack</h5>
        <div style="font-size:13px;opacity:.8;margin-top:4px">Sign in to your account</div>
    </div>
    <div class="auth-box-body">
        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Email</label>
                <input type="email" name="email" class="form-control form-control-sm"
                       value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="loginPassword" class="form-control form-control-sm pe-5"
                           placeholder="Enter your password" required>
                    <button type="button" id="toggleLoginPassword" tabindex="-1"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;color:#6c757d">
                        <i class="bi bi-eye" id="loginEyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <hr class="my-3">
        <p class="text-center mb-0" style="font-size:13px">
            No account yet? <a href="{{ route('register') }}">Register here</a>
        </p>
    </div>
</div>
<script>
    document.getElementById('toggleLoginPassword').addEventListener('click', function () {
        const input = document.getElementById('loginPassword');
        const icon = document.getElementById('loginEyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
@endsection
