@extends('layouts.app-with-sidebar')

@section('title', 'Semua Pengajuan Cuti - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-list text-primary"></i> Semua Pengajuan Cuti
            </h1>
            <p class="text-muted">Lihat dan kelola semua pengajuan cuti karyawan</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-primary me-2">
                <i class="fas fa-folder-open"></i>
                {{ $allRequests->total() }} Total
            </div>
            <a href="{{ route('admin.pending-approvals') }}" class="btn btn-sm btn-danger">
                <i class="fas fa-hourglass-end"></i> Lihat Pending
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">
                <i class="fas fa-filter"></i> Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="user_id" class="form-label">Karyawan</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">-- Semua Karyawan --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.all-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="mb-2 text-primary">{{ $allRequests->total() }}</h3>
                    <h6 class="text-muted mb-0">Total Pengajuan</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="mb-2 text-warning">
                        {{ $allRequests->where('status', 'pending')->count() }}
                    </h3>
                    <h6 class="text-muted mb-0">Pending</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="mb-2 text-success">
                        {{ $allRequests->where('status', 'approved')->count() }}
                    </h3>
                    <h6 class="text-muted mb-0">Disetujui</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h3 class="mb-2 text-danger">
                        {{ $allRequests->where('status', 'rejected')->count() }}
                    </h3>
                    <h6 class="text-muted mb-0">Ditolak</h6>
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
                        <i class="fas fa-table text-primary"></i> Daftar Pengajuan
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
            @if($allRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th style="width: 12%">Karyawan</th>
                                <th style="width: 12%">Periode</th>
                                <th style="width: 8%">Hari</th>
                                <th style="width: 15%">Alasan</th>
                                <th style="width: 8%">Status</th>
                                <th style="width: 12%">Tanggal</th>
                                <th style="width: 15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRequests as $index => $request)
                                <tr>
                                    <td>{{ ($allRequests->currentPage() - 1) * $allRequests->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($request->user->name) }}&background=667eea&color=fff&bold=true" 
                                                 alt="{{ $request->user->name }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 28px; height: 28px;">
                                            <small class="fw-bold">{{ Str::limit($request->user->name, 12) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $request->start_date->format('d/m') }} - 
                                            {{ $request->end_date->format('d/m') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $request->start_date->diffInDays($request->end_date) + 1 }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($request->reason, 30) }}</small>
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half"></i> Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $request->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.view-request', $request->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Detail">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button" 
                                                        class="btn btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#approveModal{{ $request->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $request->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                @if($request->status === 'pending')
                                    <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Setujui</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Karyawan:</strong> {{ $request->user->name }}</p>
                                                    <p><strong>Periode:</strong> {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}</p>
                                                    <div class="form-group">
                                                        <label for="approveNotes{{ $request->id }}">Catatan:</label>
                                                        <textarea class="form-control" id="approveNotes{{ $request->id }}" rows="2" placeholder="Catatan (opsional)..."></textarea>
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
                                                    <h5 class="modal-title"><i class="fas fa-times-circle"></i> Tolak</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Karyawan:</strong> {{ $request->user->name }}</p>
                                                    <p><strong>Periode:</strong> {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}</p>
                                                    <div class="form-group">
                                                        <label for="rejectReason{{ $request->id }}" class="form-label"><strong>Alasan Penolakan:</strong></label>
                                                        <textarea class="form-control" id="rejectReason{{ $request->id }}" rows="2" placeholder="Jelaskan alasan..." required></textarea>
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
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top p-4">
                    {{ $allRequests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted fa-3x mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Pengajuan</h5>
                    <p class="text-muted small">Coba sesuaikan filter Anda</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function approveRequest(requestId) {
        const notes = document.getElementById('approveNotes' + requestId).value;
        
        showLoading();
        
        fetch(`/api/leave-requests/${requestId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ notes })
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed');
            return response.json();
        })
        .then(() => {
            hideLoading();
            showToast('✅ Pengajuan berhasil disetujui', 'success');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            hideLoading();
            showToast('❌ Gagal menyetujui pengajuan', 'error');
        });
    }

    function rejectRequest(requestId) {
        const reason = document.getElementById('rejectReason' + requestId).value;
        
        if (!reason.trim()) {
            showToast('⚠️ Alasan penolakan harus diisi', 'warning');
            return;
        }
        
        showLoading();
        
        fetch(`/api/leave-requests/${requestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason })
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed');
            return response.json();
        })
        .then(() => {
            hideLoading();
            showToast('✅ Pengajuan berhasil ditolak', 'success');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            hideLoading();
            showToast('❌ Gagal menolak pengajuan', 'error');
        });
    }
</script>

<style>
    .badge {
        padding: 6px 10px;
        font-size: 11px;
    }
    
    .btn-group-sm .btn {
        padding: 0.3rem 0.5rem;
        font-size: 0.8rem;
    }
</style>
@endsection
