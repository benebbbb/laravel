@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                {{-- Avatar --}}
                @if($user->profile_picture)
                    <img src="{{ Storage::url($user->profile_picture) }}"
                         class="avatar-lg mb-3" alt="Profile Picture">
                @else
                    <div class="avatar-lg bg-primary d-flex align-items-center justify-content-center
                                text-white fw-bold fs-2 mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    @if($user->gender)
                        <span class="badge bg-info text-dark">{{ $user->gender }}</span>
                    @endif
                    <span class="badge bg-secondary">
                        Member since {{ $user->created_at->format('M Y') }}
                    </span>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </a>
            </div>

            <hr class="my-0">

            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Full Name</dt>
                    <dd class="col-sm-8">{{ $user->name }}</dd>

                    <dt class="col-sm-4 text-muted">Email</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>

                    <dt class="col-sm-4 text-muted">Gender</dt>
                    <dd class="col-sm-8">{{ $user->gender ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted">Address</dt>
                    <dd class="col-sm-8">{{ $user->address ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
