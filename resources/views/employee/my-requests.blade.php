@extends('layouts.app-with-sidebar')

@section('title', 'Pengajuan Saya - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-file-alt text-primary"></i> Pengajuan Saya
            </h1>
            <p class="text-muted">Lihat dan kelola pengajuan cuti Anda</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('employee.create-request') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Ajukan Cuti Baru
            </a>
        </div>
    </div>

    <!-- Leave Balance Info -->
    @if($currentBalance)
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-calendar-day"></i> Sisa Cuti Tahun Ini
                        </h6>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <h2 class="text-primary mb-0">{{ $currentBalance->total_days - $currentBalance->used_days }} hari</h2>
                            </div>
                            <div class="col">
                                <small class="text-muted">
                                    Dari total {{ $currentBalance->total_days }} hari
                                    <br>
                                    <span class="text-danger">{{ $currentBalance->used_days }} hari sudah dipakai</span>
                                </small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 20px;">
                            @php
                                $percentage = ($currentBalance->used_days / $currentBalance->total_days) * 100;
                            @endphp
                            <div class="progress-bar bg-@if($percentage > 70)danger @elseif($percentage > 40)warning @else success @endif" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ round($percentage, 0) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle"></i> Ringkasan Status
                        </h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="mb-1 text-warning">
                                    {{ $myRequests->sum(function($r) { return $r->status === 'pending' ? 1 : 0; }) }}
                                </h4>
                                <small class="text-muted">Pending</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-1 text-success">
                                    {{ $myRequests->sum(function($r) { return $r->status === 'approved' ? 1 : 0; }) }}
                                </h4>
                                <small class="text-muted">Disetujui</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-1 text-danger">
                                    {{ $myRequests->sum(function($r) { return $r->status === 'rejected' ? 1 : 0; }) }}
                                </h4>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">
                <i class="fas fa-filter"></i> Filter Status
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('employee.my-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="mb-0">
                <i class="fas fa-list text-primary"></i> Daftar Pengajuan Cuti Anda
            </h5>
        </div>

        <div class="card-body p-0">
            @if($myRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th style="width: 15%">Periode</th>
                                <th style="width: 10%">Hari</th>
                                <th style="width: 25%">Alasan</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Tanggal Pengajuan</th>
                                <th style="width: 20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myRequests as $index => $request)
                                <tr>
                                    <td>{{ ($myRequests->currentPage() - 1) * $myRequests->perPage() + $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $request->start_date->format('d/m/Y') }}</strong> 
                                        <br>
                                        <small class="text-muted">s/d {{ $request->end_date->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $request->start_date->diffInDays($request->end_date) + 1 }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($request->reason, 40) }}</small>
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
                                        <small class="text-muted">{{ $request->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('employee.view-request', $request->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button" 
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancelModal{{ $request->id }}"
                                                        title="Batalkan Pengajuan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Cancel Modal -->
                                @if($request->status === 'pending')
                                    <div class="modal fade" id="cancelModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Batalkan Pengajuan
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">
                                                        <strong>Anda yakin ingin membatalkan pengajuan cuti ini?</strong>
                                                    </p>
                                                    <p class="text-muted small">
                                                        Periode: {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}
                                                    </p>
                                                    <div class="alert alert-warning" role="alert">
                                                        <i class="fas fa-info-circle"></i> 
                                                        Tindakan ini tidak dapat dibatalkan. Anda dapat membuat pengajuan baru nanti.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak, Jangan</button>
                                                    <form action="{{ route('employee.cancel-request', $request->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Ya, Batalkan
                                                        </button>
                                                    </form>
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
                    {{ $myRequests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted fa-3x mb-3"></i>
                    <h5 class="text-muted">Belum Ada Pengajuan</h5>
                    <p class="text-muted small mb-3">Anda belum membuat pengajuan cuti apapun</p>
                    <a href="{{ route('employee.create-request') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ajukan Cuti Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
</style>
@endsection
