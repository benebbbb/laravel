@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted" style="font-size:11px">Total Users</div>
                    <div class="fw-bold" style="font-size:1.4rem;line-height:1.2">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success flex-shrink-0">
                    <i class="bi bi-journal-medical"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted" style="font-size:11px">My Medicines</div>
                    <div class="fw-bold" style="font-size:1.4rem;line-height:1.2">{{ $totalMedicines }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning flex-shrink-0">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted" style="font-size:11px">Expiring Soon</div>
                    <div class="fw-bold" style="font-size:1.4rem;line-height:1.2">{{ $expiringSoon }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger flex-shrink-0">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted" style="font-size:11px">Expired</div>
                    <div class="fw-bold" style="font-size:1.4rem;line-height:1.2">{{ $expired }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-bottom fw-semibold">
                <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                Medicines Added per Month ({{ now()->year }})
            </div>
            <div class="card-body p-2 p-md-3">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-bottom fw-semibold">
                <i class="bi bi-pie-chart-fill text-success me-2"></i>
                Medicines by Category
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-2 p-md-3">
                @if($byCategory->isEmpty())
                    <p class="text-muted text-center">No medicine data yet.</p>
                @else
                    <canvas id="pieChart" style="max-height:220px"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="card shadow-sm">
    <div class="card-body d-flex flex-wrap gap-2 p-3">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
            <i class="bi bi-plus-lg me-1"></i> Add Medicine
        </button>
        <a href="{{ route('medicines.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-list-ul me-1"></i> View All Medicines
        </a>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit Profile
        </a>
    </div>
</div>

{{-- Add Medicine Modal --}}
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Medicine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('medicines.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                        <input type="text" name="medicine_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach(['Tablet','Syrup','Capsule','Injection','Ointment','Drops','Other'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" min="0" class="form-control" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiration_date" class="form-control" required>
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Medicines Added',
                data: @json($monthlyData),
                backgroundColor: 'rgba(13,110,253,.75)',
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    @if($byCategory->isNotEmpty())
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: @json($byCategory->keys()->values()),
            datasets: [{
                data: @json($byCategory->values()->values()),
                backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#0dcaf0','#6f42c1','#fd7e14'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 10, font: { size: 11 } } }
            }
        }
    });
    @endif
</script>
@endpush
