@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        <i class="bi bi-people-fill me-2 text-primary"></i>Users Management
    </h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add
    </button>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('users.index') }}" class="card card-body mb-3 p-3 shadow-sm">
    <div class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Search by name or email..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-search"></i></button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm px-3"><i class="bi bi-x"></i></a>
    </div>
</form>

{{-- Desktop Table --}}
<div class="card shadow-sm d-none d-md-block">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" class="rounded-circle" width="34" height="34" style="object-fit:cover" alt="avatar">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:34px;height:34px;font-size:.8rem;flex-shrink:0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold" style="font-size:.9rem">{{ $user->name }}</div>
                                        @if($user->id === Auth::id())
                                            <span class="badge bg-success" style="font-size:.7rem">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                @if($user->id === Auth::id())
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                        onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i> No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Mobile Card List --}}
<div class="d-md-none">
    @forelse($users as $user)
        <div class="card shadow-sm mb-2">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        @if($user->profile_picture)
                            <img src="{{ Storage::url($user->profile_picture) }}" class="rounded-circle" width="40" height="40" style="object-fit:cover;flex-shrink:0" alt="avatar">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:40px;height:40px;font-size:.9rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="fw-semibold" style="font-size:14px">
                                {{ $user->name }}
                                @if($user->id === Auth::id())
                                    <span class="badge bg-success ms-1" style="font-size:10px">You</span>
                                @endif
                            </div>
                            <div class="text-muted" style="font-size:12px">{{ $user->email }}</div>
                            <div class="text-muted" style="font-size:11px"><i class="bi bi-calendar3 me-1"></i>{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        @if($user->id === Auth::id())
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No users found.
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $users->links() }}
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any() && old('_form') === 'add')
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="_form" value="add">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-person-gear me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editUserForm">
                @csrf @method('PUT')
                <div class="modal-body">
                    @if($errors->any() && old('_form') === 'edit')
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="_form" value="edit">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editUserName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="editUserEmail" class="form-control" required>
                    </div>
                    <hr>
                    <p class="text-muted small mb-3">Leave password blank to keep current password.</p>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-check-lg me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete <strong id="deleteUserName"></strong>? This cannot be undone.</div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteUserName').textContent = name;
        deleteModal.show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
        if (deleteId) document.getElementById('delete-form-' + deleteId).submit();
    });
    function openEditUser(id, name, email) {
        document.getElementById('editUserForm').action = '/users/' + id;
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
    @if($errors->any() && old('_form') === 'add')
        new bootstrap.Modal(document.getElementById('addUserModal')).show();
    @endif
    @if($errors->any() && old('_form') === 'edit')
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    @endif
</script>
@endpush
