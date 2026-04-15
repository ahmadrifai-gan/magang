@extends('layouts.app-with-sidebar')

@section('title', 'Detail Pengajuan Cuti - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Request Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt text-primary"></i> Detail Pengajuan
                            </h5>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-@if($request->status === 'pending')warning @elseif($request->status === 'approved')success @else danger @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Karyawan Info -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user"></i> Informasi Karyawan
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Nama:</strong> 
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($request->user->name) }}&background=667eea&color=fff&bold=true" 
                                         alt="{{ $request->user->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 24px; height: 24px;">
                                    {{ $request->user->name }}
                                </p>
                                <p>
                                    <strong>Email:</strong> {{ $request->user->email }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>Role:</strong> 
                                    <span class="badge bg-primary">{{ ucfirst($request->user->role) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Periode Cuti -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-calendar"></i> Periode Cuti
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Tanggal Mulai:</strong><br>
                                    {{ $request->start_date->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $request->start_date->translatedFormat('l') }}</small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Tanggal Akhir:</strong><br>
                                    {{ $request->end_date->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $request->end_date->translatedFormat('l') }}</small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <strong>Total Hari:</strong><br>
                                    <h5 class="text-primary mb-0">{{ $request->start_date->diffInDays($request->end_date) + 1 }} hari kerja</h5>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Alasan & Lampiran -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-pen"></i> Detail Pengajuan
                        </h6>
                        <p>
                            <strong>Alasan Cuti:</strong><br>
                            {{ $request->reason }}
                        </p>
                        
                        @if($request->attachment_path)
                            <p>
                                <strong>Lampiran:</strong><br>
                                <a href="{{ Storage::url($request->attachment_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </p>
                        @endif
                    </div>

                    <hr>

                    <!-- Tanggal Pengajuan -->
                    <div>
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-clock"></i> Riwayat
                        </h6>
                        <p class="mb-1">
                            <strong>Diajukan:</strong> 
                            <small class="text-muted">{{ $request->created_at->format('d M Y H:i') }}</small>
                        </p>
                        @if($request->updated_at && $request->updated_at != $request->created_at)
                            <p>
                                <strong>Diperbarui:</strong> 
                                <small class="text-muted">{{ $request->updated_at->format('d M Y H:i') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Status Pengajuan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($request->status === 'pending')
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                                <h6 class="mb-0">Menunggu Persetujuan</h6>
                            </div>
                        @elseif($request->status === 'approved')
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h6 class="mb-0">Disetujui</h6>
                            </div>
                            @if($request->approver)
                                <p class="mb-2 text-muted">
                                    <strong>Disetujui oleh:</strong><br>
                                    {{ $request->approver->name }}
                                </p>
                            @endif
                            @if($request->notes)
                                <p class="bg-light p-2 rounded">
                                    <small><strong>Catatan:</strong> {{ $request->notes }}</small>
                                </p>
                            @endif
                        @else
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle fa-2x mb-2"></i>
                                <h6 class="mb-0">Ditolak</h6>
                            </div>
                            @if($request->approver)
                                <p class="mb-2 text-muted">
                                    <strong>Ditolak oleh:</strong><br>
                                    {{ $request->approver->name }}
                                </p>
                            @endif
                            @if($request->notes)
                                <p class="bg-light p-2 rounded">
                                    <small><strong>Alasan:</strong> {{ $request->notes }}</small>
                                </p>
                            @endif
                        @endif
                    </div>

                    @if($request->status === 'pending')
                        <button type="button" 
                                class="btn btn-success w-100 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                            <i class="fas fa-check"></i> Setujui Pengajuan
                        </button>
                        <button type="button" 
                                class="btn btn-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Tolak Pengajuan
                        </button>
                    @else
                        <p class="text-muted small mb-0">
                            Pengajuan sudah diproses dan tidak dapat diubah
                        </p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-lightning-bolt"></i> Tindakan Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.pending-approvals') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-hourglass-end"></i> Lihat Pending
                    </a>
                    <a href="{{ route('admin.all-requests') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if($request->status === 'pending')
    <div class="modal fade" id="approveModal" tabindex="-1">
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
                    <hr>
                    <div class="form-group">
                        <label for="approveNotes" class="form-label">Catatan (Opsional):</label>
                        <textarea class="form-control" id="approveNotes" rows="3" placeholder="Tulis catatan persetujuan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="approveRequest()">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
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
                    <hr>
                    <div class="form-group">
                        <label for="rejectReason" class="form-label"><strong>Alasan Penolakan:</strong></label>
                        <textarea class="form-control" id="rejectReason" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                        <small class="text-muted">Pesan ini akan diberitahukan ke karyawan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="rejectRequest()">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Scripts -->
<script src="{{ asset('js/api-client.js') }}"></script>
<script>
    function approveRequest() {
        const notes = document.getElementById('approveNotes')?.value || '';
        const requestId = {{ $request->id }};
        approveLeaveRequest(requestId, notes);
    }

    function rejectRequest() {
        const reason = document.getElementById('rejectReason')?.value || '';
        const requestId = {{ $request->id }};
        rejectLeaveRequest(requestId, reason);
    }
</script>
@endsection
