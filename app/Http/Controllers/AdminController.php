<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Middleware untuk ensure user is admin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Tampilkan pending approvals
     */
    public function pendingApprovals(Request $request)
    {
        $pendingRequests = LeaveRequest::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pending-approvals', [
            'pendingRequests' => $pendingRequests,
            'title' => 'Persetujuan Tertunda',
        ]);
    }

    /**
     * Tampilkan semua pengajuan dengan filter
     */
    public function allRequests(Request $request)
    {
        $query = LeaveRequest::with('user', 'approver')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan user jika ada
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        $allRequests = $query->paginate(15);

        // Get list of users untuk filter dropdown
        $users = User::where('role', 'employee')
            ->orderBy('name')
            ->get();

        return view('admin.all-requests', [
            'allRequests' => $allRequests,
            'users' => $users,
            'title' => 'Semua Pengajuan Cuti',
        ]);
    }

    /**
     * View detail pengajuan
     */
    public function viewRequest(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('user', 'approver');

        return view('admin.view-request', [
            'request' => $leaveRequest,
        ]);
    }
}
