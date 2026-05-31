@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4 px-3">
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

                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    @if($user->gender)
                        <span class="badge bg-info text-dark">{{ $user->gender }}</span>
                    @endif
                    <span class="badge bg-secondary">
                        Member since {{ $user->created_at->format('M Y') }}
                    </span>
                </div>

                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                    <form method="POST" action="{{ route('profile.destroy') }}"
                          onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-4">
                            <i class="bi bi-trash me-1"></i> Delete Account
                        </button>
                    </form>
                </div>
            </div>

            <hr class="my-0">

            <div class="card-body px-3 px-md-4">
                <dl class="mb-0" style="font-size:13.5px">
                    <div class="d-flex py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="min-width:110px">Full Name</dt>
                        <dd class="mb-0 fw-semibold">{{ $user->name }}</dd>
                    </div>
                    <div class="d-flex py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="min-width:110px">Email</dt>
                        <dd class="mb-0 text-break">{{ $user->email }}</dd>
                    </div>
                    <div class="d-flex py-2 border-bottom">
                        <dt class="text-muted fw-normal" style="min-width:110px">Gender</dt>
                        <dd class="mb-0">{{ $user->gender ?? '—' }}</dd>
                    </div>
                    <div class="d-flex py-2">
                        <dt class="text-muted fw-normal" style="min-width:110px">Address</dt>
                        <dd class="mb-0">{{ $user->address ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
