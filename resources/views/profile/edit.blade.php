@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom fw-semibold">
                <i class="bi bi-person-gear me-2 text-primary"></i> Edit Profile
            </div>
            <div class="card-body p-3 p-md-4">

                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Profile Picture --}}
                    <div class="mb-4 text-center">
                        @if($user->profile_picture)
                            <img src="{{ Storage::url($user->profile_picture) }}"
                                 class="avatar-lg mb-2" id="avatarPreview" alt="avatar">
                        @else
                            <div class="avatar-lg bg-primary d-flex align-items-center justify-content-center
                                        text-white fw-bold fs-2 mx-auto mb-2" id="avatarPlaceholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img src="" class="avatar-lg mb-2 d-none" id="avatarPreview" alt="avatar">
                        @endif
                        <div>
                            <label for="profile_picture" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-camera me-1"></i> Change Photo
                            </label>
                            <input type="file" name="profile_picture" id="profile_picture"
                                   class="d-none @error('profile_picture') is-invalid @enderror"
                                   accept="image/*">
                            @error('profile_picture')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">— Select —</option>
                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $user->address) }}">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr>
                    <p class="text-muted small mb-3">Leave password fields blank to keep your current password.</p>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="newPassword"
                                   class="form-control @error('password') is-invalid @enderror pe-5"
                                   autocomplete="new-password">
                            <button type="button" tabindex="-1" onclick="togglePass('newPassword','eyeNew')"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;color:#6c757d">
                                <i class="bi bi-eye" id="eyeNew"></i>
                            </button>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="confirmPassword"
                                   class="form-control pe-5">
                            <button type="button" tabindex="-1" onclick="togglePass('confirmPassword','eyeConfirm')"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;color:#6c757d">
                                <i class="bi bi-eye" id="eyeConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary flex-fill">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
    document.getElementById('profile_picture').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
