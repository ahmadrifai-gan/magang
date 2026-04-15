@extends('layouts.app-with-sidebar')

@section('title', 'Ajukan Cuti - Leave Management System')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6 fw-bold">
                <i class="fas fa-plus-circle text-primary"></i> Ajukan Cuti Baru
            </h1>
            <p class="text-muted">Isi formulir pengajuan cuti Anda di bawah ini</p>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-pencil-alt text-primary"></i> Detail Pengajuan
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('employee.store-request') }}" method="POST" enctype="multipart/form-data" id="leaveForm">
                        @csrf

                        <!-- Tanggal Mulai -->
                        <div class="mb-4">
                            <label for="start_date" class="form-label">
                                <strong>
                                    <i class="fas fa-calendar"></i> Tanggal Mulai Cuti
                                </strong>
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            <small class="text-muted">Tanggal mulai cuti (tidak boleh di masa lalu)</small>
                            @error('start_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Akhir -->
                        <div class="mb-4">
                            <label for="end_date" class="form-label">
                                <strong>
                                    <i class="fas fa-calendar"></i> Tanggal Akhir Cuti
                                </strong>
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            <small class="text-muted">Tanggal akhir cuti (harus >= tanggal mulai)</small>
                            @error('end_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Jumlah Hari -->
                        <div class="alert alert-info" id="daysInfo" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Total Hari Cuti: <span id="totalDays">0</span> hari kerja</strong>
                        </div>

                        <!-- Alasan Cuti -->
                        <div class="mb-4">
                            <label for="reason" class="form-label">
                                <strong>
                                    <i class="fas fa-pen"></i> Alasan Cuti
                                </strong>
                            </label>
                            <textarea class="form-control form-control-lg @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4"
                                      placeholder="Jelaskan alasan pengajuan cuti Anda dengan terperinci..."
                                      minlength="10"
                                      maxlength="500"
                                      required>{{ old('reason') }}</textarea>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">Min 10 - Max 500 karakter</small>
                                <small class="text-muted"><span id="charCount">0</span>/500</small>
                            </div>
                            @error('reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lampiran -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label">
                                <i class="fas fa-paperclip"></i> Lampiran Berkas (Opsional)
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="file" 
                                       class="form-control @error('attachment') is-invalid @enderror" 
                                       id="attachment" 
                                       name="attachment"
                                       accept=".pdf,.doc,.docx,.jpg,.png"
                                       onchange="updateFileName(this)">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Format: PDF, DOC, DOCX, JPG, PNG (Max 5MB)
                                <br>
                                Contoh: Surat sakit, surat izin, atau dokumen pendukung lainnya
                            </small>
                            @error('attachment')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Ajukan Sekarang
                            </button>
                            <a href="{{ route('employee.my-requests') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <!-- Leave Balance -->
            @if($currentBalance)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-check-circle"></i> Sisa Cuti Anda
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-success mb-2">{{ $currentBalance->total_days - $currentBalance->used_days }} hari</h2>
                        <p class="text-muted small mb-3">Dari total {{ $currentBalance->total_days }} hari kerja</p>
                        
                        <div class="progress mb-3" style="height: 25px;">
                            @php
                                $percentage = ($currentBalance->used_days / $currentBalance->total_days) * 100;
                            @endphp
                            <div class="progress-bar bg-danger" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ round($percentage, 0) }}% Terpakai
                            </div>
                        </div>

                        <div class="row text-center g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Tersedia</small>
                                <h5 class="text-success mb-0">{{ $currentBalance->total_days - $currentBalance->used_days }}</h5>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Terpakai</small>
                                <h5 class="text-danger mb-0">{{ $currentBalance->used_days }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tips Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Tips & Ketentuan
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>✓ Panduan Pengajuan:</strong></p>
                    <ul class="small text-muted">
                        <li>Ajukan cuti minimal 2 hari sebelum tanggal cuti dimulai</li>
                        <li>Pastikan tanggal tidak bentrok dengan cuti yang sudah disetujui</li>
                        <li>Jelaskan alasan cuti dengan terperinci</li>
                        <li>Lampirkan dokumen pendukung jika diperlukan</li>
                        <li>Admin akan merespon dalam 2x24 jam kerja</li>
                    </ul>

                    <hr>

                    <p class="small mb-2"><strong>📋 Status Pengajuan:</strong></p>
                    <ul class="small text-muted">
                        <li><span class="badge bg-warning text-dark">Pending</span> - Menunggu review</li>
                        <li><span class="badge bg-success">Disetujui</span> - Cuti diterima</li>
                        <li><span class="badge bg-danger">Ditolak</span> - Cuti tidak diterima</li>
                    </ul>
                </div>
            </div>

            <!-- Recent Approvals -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-history"></i> Pengajuan Terbaru
                    </h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @php
                        $recentRequests = \App\Models\LeaveRequest::where('user_id', Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentRequests->count() > 0)
                        @foreach($recentRequests as $req)
                            <div class="mb-3 pb-3 border-bottom small">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong>{{ $req->start_date->format('d/m/Y') }}</strong>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-success">✓ OK</span>
                                    @else
                                        <span class="badge bg-danger">✗ Tolak</span>
                                    @endif
                                </div>
                                <small class="text-muted d-block">
                                    {{ $req->start_date->diffInDays($req->end_date) + 1 }} hari | 
                                    {{ $req->created_at->diffForHumans() }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small text-center py-3">Belum ada pengajuan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const daysInfo = document.getElementById('daysInfo');
    const totalDaysSpan = document.getElementById('totalDays');
    const reasonInput = document.getElementById('reason');
    const charCountSpan = document.getElementById('charCount');

    // Update days count
    function updateDaysCount() {
        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            
            if (start <= end) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                totalDaysSpan.textContent = diffDays;
                daysInfo.style.display = 'block';
            }
        }
    }

    startDateInput.addEventListener('change', updateDaysCount);
    endDateInput.addEventListener('change', updateDaysCount);

    // Update character count
    reasonInput.addEventListener('input', function() {
        charCountSpan.textContent = this.value.length;
    });

    // Update file name display
    function updateFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024).toFixed(2);
            
            if (input.files[0].size > 5 * 1024 * 1024) {
                alert('File terlalu besar! Maksimal 5MB');
                input.value = '';
                return;
            }
            
            console.log(`✓ File dipilih: ${fileName} (${fileSize} KB)`);
        }
    }

    // Form validation
    document.getElementById('leaveForm').addEventListener('submit', function(e) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const reasonLength = reasonInput.value.trim().length;

        // Validasi alasan
        if (reasonLength < 10) {
            e.preventDefault();
            alert('Alasan cuti minimal 10 karakter');
            reasonInput.focus();
            return false;
        }

        // Validasi tanggal
        if (startDate > endDate) {
            e.preventDefault();
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            endDateInput.focus();
            return false;
        }
    });
</script>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .input-group-text {
        border-color: #dee2e6;
    }

    .alert {
        border: none;
        border-radius: 8px;
    }

    .badge {
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 500;
    }
</style>
@endsection
