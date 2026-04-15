@extends('layouts.app-with-sidebar')

@section('title', 'Persetujuan Tertunda - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-hourglass-end text-danger"></i> Persetujuan Tertunda
            </h1>
            <p class="text-muted">Kelola pengajuan cuti yang menunggu persetujuan Anda</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-danger me-2">
                <i class="fas fa-bell"></i>
                {{ $pendingRequests->total() }} Pengajuan
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Pending</h6>
                    <h3 class="mb-0 text-danger">{{ $pendingRequests->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Halaman Ini</h6>
                    <h3 class="mb-0 text-primary">{{ $pendingRequests->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Status</h6>
                    <h3 class="mb-0 text-warning">Pending</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Action</h6>
                    <a href="{{ route('admin.all-requests') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-primary"></i> Daftar Pengajuan Pending
                    </h5>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($pendingRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th style="width: 15%">Karyawan</th>
                                <th style="width: 15%">Tanggal Pengajuan</th>
                                <th style="width: 15%">Periode Cuti</th>
                                <th style="width: 30%">Alasan</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $index => $request)
                                <tr>
                                    <td>{{ ($pendingRequests->currentPage() - 1) * $pendingRequests->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($request->user->name) }}&background=667eea&color=fff&bold=true" 
                                                 alt="{{ $request->user->name }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px;">
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $request->user->name }}</p>
                                                <small class="text-muted">{{ $request->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $request->start_date->format('d/m/Y') }}</strong> 
                                        <br>
                                        <small class="text-muted">
                                            s/d {{ $request->end_date->format('d/m/Y') }}
                                        </small>
                                        <br>
                                        <badge class="badge bg-info">{{ $request->start_date->diffInDays($request->end_date) + 1 }} hari</badge>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($request->reason, 50) }}</small>
                                        @if(strlen($request->reason) > 50)
                                            <br><small class="text-primary" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detailModal{{ $request->id }}">
                                                Lihat selengkapnya
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-half"></i> Pending
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                    class="btn btn-outline-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $request->id }}"
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $request->id }}"
                                                    title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <a href="{{ route('admin.view-request', $request->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-check-circle"></i> Setujui Pengajuan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Karyawan:</strong> {{ $request->user->name }}</p>
                                                <p><strong>Periode:</strong> {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}</p>
                                                <p><strong>Alasan:</strong> {{ $request->reason }}</p>
                                                <hr>
                                                <div class="form-group">
                                                    <label for="approveNotes{{ $request->id }}">Catatan (Opsional):</label>
                                                    <textarea class="form-control" id="approveNotes{{ $request->id }}" rows="3" placeholder="Tulis catatan persetujuan..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-success" onclick="approveRequest({{ $request->id }})">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-times-circle"></i> Tolak Pengajuan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Karyawan:</strong> {{ $request->user->name }}</p>
                                                <p><strong>Periode:</strong> {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}</p>
                                                <p><strong>Alasan Pengajuan:</strong> {{ $request->reason }}</p>
                                                <hr>
                                                <div class="form-group">
                                                    <label for="rejectReason{{ $request->id }}" class="form-label"><strong>Alasan Penolakan:</strong></label>
                                                    <textarea class="form-control" id="rejectReason{{ $request->id }}" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                                    <small class="text-muted">Pesan ini akan diberitahukan ke karyawan</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-danger" onclick="rejectRequest({{ $request->id }})">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top p-4">
                    {{ $pendingRequests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Pengajuan Pending</h5>
                    <p class="text-muted small">Semua pengajuan cuti sudah diproses!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Action Scripts -->
<script src="{{ asset('js/api-client.js') }}"></script>
<script>
    function approveRequest(requestId) {
        const notes = document.getElementById('approveNotes' + requestId)?.value || '';
        approveLeaveRequest(requestId, notes);
    }

    function rejectRequest(requestId) {
        const reason = document.getElementById('rejectReason' + requestId)?.value || '';
        rejectLeaveRequest(requestId, reason);
    }
</script>

<style>
    .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
    
    .table-hover tbody tr {
        cursor: pointer;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
