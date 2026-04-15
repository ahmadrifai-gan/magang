@extends('layouts.app-with-sidebar')

@section('title', 'Detail Pengajuan Cuti - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('employee.my-requests') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt text-primary"></i> Detail Pengajuan Cuti
                            </h5>
                        </div>
                        <div class="col-auto">
                            @if($request->status === 'pending')
                                <span class="badge bg-warning text-dark" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-hourglass-half"></i> Menunggu Persetujuan
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="badge bg-success" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger" style="font-size: 13px; padding: 8px 12px;">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Periode Cuti -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-calendar"></i> Periode Cuti
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Tanggal Mulai:</strong><br>
                                    <h5 class="text-primary">{{ $request->start_date->format('d M Y') }}</h5>
                                    <small class="text-muted">{{ $request->start_date->translatedFormat('l') }}</small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Tanggal Akhir:</strong><br>
                                    <h5 class="text-primary">{{ $request->end_date->format('d M Y') }}</h5>
                                    <small class="text-muted">{{ $request->end_date->translatedFormat('l') }}</small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Total Hari:</strong><br>
                                    <h5 class="text-success">{{ $request->start_date->diffInDays($request->end_date) + 1 }} hari</h5>
                                    <small class="text-muted">hari kerja</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Alasan & Lampiran -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-pen"></i> Keterangan Pengajuan
                        </h6>
                        <p>
                            <strong>Alasan Cuti:</strong><br>
                            <span class="text-muted">{{ $request->reason }}</span>
                        </p>

                        @if($request->attachment_path)
                            <p class="mt-3">
                                <strong>Lampiran Berkas:</strong><br>
                                <a href="{{ Storage::url($request->attachment_path) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   target="_blank" 
                                   download>
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </p>
                        @endif
                    </div>

                    <hr>

                    <!-- Status History -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-history"></i> Riwayat
                        </h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <p class="mb-1"><strong>Pengajuan Dibuat</strong></p>
                                    <small class="text-muted">{{ $request->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>

                            @if($request->status !== 'pending')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-@if($request->status === 'approved')success @else danger @endif"></div>
                                    <div class="timeline-content">
                                        <p class="mb-1">
                                            <strong>
                                                @if($request->status === 'approved')
                                                    ✓ Pengajuan Disetujui
                                                @else
                                                    ✗ Pengajuan Ditolak
                                                @endif
                                            </strong>
                                        </p>
                                        @if($request->approver)
                                            <small class="text-muted">
                                                oleh {{ $request->approver->name }}<br>
                                                {{ $request->updated_at->format('d M Y H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                @if($request->notes)
                                    <div class="alert alert-@if($request->status === 'approved')success @else danger @endif mt-3">
                                        <strong>
                                            @if($request->status === 'approved')
                                                📝 Catatan Persetujuan:
                                            @else
                                                ⚠️ Alasan Penolakan:
                                            @endif
                                        </strong><br>
                                        {{ $request->notes }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    @if($request->status === 'pending')
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                            <h6 class="mb-2">Menunggu Persetujuan</h6>
                            <p class="small mb-0">Admin akan meninjaunya dalam 2x24 jam</p>
                        </div>
                    @elseif($request->status === 'approved')
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h6 class="mb-2">Disetujui</h6>
                            <p class="small mb-0">Pengajuan cuti Anda telah diterima</p>
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h6 class="mb-2">Ditolak</h6>
                            <p class="small mb-0">Silakan buat pengajuan cuti yang lain</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-lightning-bolt"></i> Tindakan
                    </h6>
                </div>
                <div class="card-body">
                    @if($request->status === 'pending')
                        <button type="button" 
                                class="btn btn-danger w-100 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#cancelModal">
                            <i class="fas fa-trash"></i> Batalkan Pengajuan
                        </button>
                    @endif
                    
                    <a href="{{ route('employee.my-requests') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-list"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informasi
                    </h6>
                </div>
                <div class="card-body small">
                    <p class="mb-2"><strong>ID Pengajuan:</strong></p>
                    <code class="bg-light p-2 d-block mb-3">#{{ $request->id }}</code>

                    <p class="mb-2"><strong>Status Terkini:</strong></p>
                    <p>
                        @if($request->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span> - Menunggu review dari admin
                        @elseif($request->status === 'approved')
                            <span class="badge bg-success">Disetujui</span> - Cuti Anda telah diterima
                        @else
                            <span class="badge bg-danger">Ditolak</span> - Cuti tidak dapat disetujui
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
@if($request->status === 'pending')
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Batalkan Pengajuan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><strong>Anda yakin ingin membatalkan pengajuan ini?</strong></p>
                    <p class="text-muted">
                        <strong>Periode:</strong> {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}<br>
                        <strong>Durasi:</strong> {{ $request->start_date->diffInDays($request->end_date) + 1 }} hari
                    </p>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-warning"></i> Tindakan ini tidak dapat dibatalkan. Anda dapat membuat pengajuan cuti baru nanti.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak, Jangan</button>
                    <form action="{{ route('employee.cancel-request', $request->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Ya, Batalkan Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
        border-left: 2px solid #dee2e6;
    }

    .timeline-item:last-child {
        border-left: none;
    }

    .timeline-marker {
        position: absolute;
        left: -12px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px currentColor;
    }

    .timeline-content {
        padding-left: 10px;
    }

    .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
    }
</style>
@endsection
