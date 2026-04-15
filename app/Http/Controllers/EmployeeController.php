<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Middleware untuk ensure user is employee
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isEmployee()) {
                return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya employee yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Tampilkan pengajuan cuti milik sendiri
     */
    public function myRequests(Request $request)
    {
        $user = Auth::user();
        
        $query = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $myRequests = $query->paginate(10);

        // Get current leave balance
        $currentBalance = $user->getCurrentYearBalance();

        return view('employee.my-requests', [
            'myRequests' => $myRequests,
            'currentBalance' => $currentBalance,
            'title' => 'Pengajuan Saya',
        ]);
    }

    /**
     * Tampilkan form ajukan cuti
     */
    public function createRequest()
    {
        $user = Auth::user();
        $currentBalance = $user->getCurrentYearBalance();

        return view('employee.create-request', [
            'currentBalance' => $currentBalance,
            'title' => 'Ajukan Cuti',
        ]);
    }

    /**
     * Handle form submission ajukan cuti
     */
    public function storeRequest(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ], [
            'start_date.required' => 'Tanggal mulai harus diisi',
            'start_date.date' => 'Format tanggal tidak valid',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu',
            'end_date.required' => 'Tanggal akhir harus diisi',
            'end_date.date' => 'Format tanggal tidak valid',
            'end_date.after_or_equal' => 'Tanggal akhir harus >= tanggal mulai',
            'reason.required' => 'Alasan cuti harus diisi',
            'reason.min' => 'Alasan cuti minimal 10 karakter',
            'reason.max' => 'Alasan cuti maksimal 500 karakter',
            'attachment.mimes' => 'File harus PDF, DOC, DOCX, JPG, atau PNG',
            'attachment.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            // Handle file upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('leave-attachments', 'public');
            }

            // Create leave request
            $leaveRequest = LeaveRequest::create([
                'user_id' => $user->id,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'reason' => $validated['reason'],
                'attachment_path' => $attachmentPath,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('employee.my-requests')
                ->with('success', 'Pengajuan cuti berhasil dibuat! Admin akan meninjaunya dalam 2x24 jam.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * View detail pengajuan milik sendiri
     */
    public function viewRequest(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Check authorization - hanya bisa lihat pengajuan milik sendiri
        if ($leaveRequest->user_id !== $user->id) {
            return redirect('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengajuan ini');
        }

        $leaveRequest->load('approver');

        return view('employee.view-request', [
            'request' => $leaveRequest,
        ]);
    }

    /**
     * Cancel pengajuan (hanya jika status pending)
     */
    public function cancelRequest(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Check authorization
        if ($leaveRequest->user_id !== $user->id) {
            return redirect('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengajuan ini');
        }

        // Check status
        if ($leaveRequest->status !== 'pending') {
            return redirect()
                ->route('employee.my-requests')
                ->with('error', 'Hanya pengajuan dengan status pending yang bisa dibatalkan');
        }

        try {
            // Delete attachment if exists
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }

            // Delete the request
            $leaveRequest->delete();

            return redirect()
                ->route('employee.my-requests')
                ->with('success', 'Pengajuan cuti berhasil dibatalkan');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal membatalkan pengajuan: ' . $e->getMessage()]);
        }
    }
}
