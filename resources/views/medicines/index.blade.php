@extends('layouts.app')

@section('title', 'My Medicines')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-medical me-2 text-primary"></i>My Medicine List</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
        <i class="bi bi-plus-lg me-1"></i> Add Medicine
    </button>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('medicines.index') }}" class="card card-body mb-3 p-3 shadow-sm">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small mb-1">Search by Name</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Medicine name..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Category</label>
            <select name="category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach(['Tablet', 'Syrup', 'Capsule', 'Injection', 'Ointment', 'Drops', 'Other'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Expiration Date</label>
            <input type="date" name="expiration_date" class="form-control form-control-sm" value="{{ request('expiration_date') }}">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-x"></i></a>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Expiration Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        @php
                            $daysLeft = now()->diffInDays($medicine->expiration_date, false);
                            $isExpired = $daysLeft < 0;
                            $isNear = $daysLeft >= 0 && $daysLeft <= 7;
                        @endphp
                        <tr class="{{ $isExpired ? 'table-danger' : ($isNear ? 'table-warning' : '') }}">
                            <td>{{ $loop->iteration + ($medicines->currentPage() - 1) * $medicines->perPage() }}</td>
                            <td>
                                {{ $medicine->medicine_name }}
                                @if($isExpired)
                                    <span class="badge bg-danger ms-1">Expired</span>
                                @elseif($isNear)
                                    <span class="badge bg-warning text-dark ms-1">Expiring Soon</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $medicine->category }}</span></td>
                            <td>{{ $medicine->quantity }}</td>
                            <td>{{ $medicine->expiration_date->format('M d, Y') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    onclick="openEditMedicine(
                                        {{ $medicine->id }},
                                        '{{ addslashes($medicine->medicine_name) }}',
                                        '{{ addslashes($medicine->description) }}',
                                        '{{ $medicine->category }}',
                                        {{ $medicine->quantity }},
                                        '{{ $medicine->expiration_date->format('Y-m-d') }}'
                                    )">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete({{ $medicine->id }}, '{{ addslashes($medicine->medicine_name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $medicine->id }}"
                                    action="{{ route('medicines.destroy', $medicine) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                No medicines found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $medicines->links() }}
</div>

{{-- Add Medicine Modal --}}
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Medicine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('medicines.store') }}">
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
                        <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                        <input type="text" name="medicine_name"
                            class="form-control @error('medicine_name') is-invalid @enderror"
                            value="{{ old('medicine_name') }}" required>
                        @error('medicine_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                @foreach(['Tablet', 'Syrup', 'Capsule', 'Injection', 'Ointment', 'Drops', 'Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" min="0"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity') }}" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiration_date"
                            class="form-control @error('expiration_date') is-invalid @enderror"
                            value="{{ old('expiration_date') }}" required>
                        @error('expiration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Save Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Medicine Modal --}}
<div class="modal fade" id="editMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editMedicineForm">
                @csrf
                @method('PUT')
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
                        <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                        <input type="text" name="medicine_name" id="editMedicineName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="editMedicineDesc" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" id="editMedicineCategory" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach(['Tablet', 'Syrup', 'Capsule', 'Injection', 'Ointment', 'Drops', 'Other'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="editMedicineQty" min="0" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiration_date" id="editMedicineExp" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Update Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteMedicineName"></strong>?
            </div>
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
    // Delete
    let deleteId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteMedicineName').textContent = name;
        deleteModal.show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteId) document.getElementById('delete-form-' + deleteId).submit();
    });

    // Edit
    function openEditMedicine(id, name, desc, category, qty, exp) {
        document.getElementById('editMedicineForm').action = '/medicines/' + id;
        document.getElementById('editMedicineName').value = name;
        document.getElementById('editMedicineDesc').value = desc;
        document.getElementById('editMedicineCategory').value = category;
        document.getElementById('editMedicineQty').value = qty;
        document.getElementById('editMedicineExp').value = exp;
        new bootstrap.Modal(document.getElementById('editMedicineModal')).show();
    }

    // Re-open modals on validation error
    @if($errors->any() && old('_form') === 'add')
        new bootstrap.Modal(document.getElementById('addMedicineModal')).show();
    @endif
    @if($errors->any() && old('_form') === 'edit')
        new bootstrap.Modal(document.getElementById('editMedicineModal')).show();
    @endif
</script>
@endpush
