<?php

namespace App\Http\Controllers\Api;

use App\DTO\ApproveLeaveRequestDTO;
use App\DTO\CreateLeaveRequestDTO;
use App\DTO\RejectLeaveRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveLeaveRequestRequest;
use App\Http\Requests\RejectLeaveRequestRequest;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Resources\LeaveBalanceResource;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveRequestController extends Controller
{
    public function __construct(private LeaveRequestService $leaveRequestService)
    {
    }

    /**
     * Get all leave requests (admin only) or user's leave requests (employee)
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $user = $request->user();

        // Employees can only see their own requests
        if ($user->isEmployee()) {
            $leaveRequests = $this->leaveRequestService->getUserLeaveRequests($user->id);
            return LeaveRequestResource::collection($leaveRequests);
        }

        // Admins can see all requests and filter by user or status
        $status = $request->query('status');
        $userId = $request->query('user_id');

        $leaveRequests = $this->leaveRequestService->getAllLeaveRequests($status, $userId);

        return LeaveRequestResource::collection($leaveRequests);
    }

    /**
     * Store a new leave request
     */
    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        try {
            $attachmentPath = null;

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('leave-attachments', 'public');
            }

            $dto = new CreateLeaveRequestDTO(
                user_id: $request->user()->id,
                start_date: new DateTime($request->input('start_date')),
                end_date: new DateTime($request->input('end_date')),
                reason: $request->input('reason'),
                attachment_path: $attachmentPath,
            );

            $leaveRequest = $this->leaveRequestService->create($dto);

            return response()->json([
                'message' => 'Pengajuan cuti berhasil dibuat',
                'data' => new LeaveRequestResource($leaveRequest),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat pengajuan cuti',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get a specific leave request
     */
    public function show(LeaveRequest $leaveRequest, Request $request): JsonResponse
    {
        // Check authorization
        $user = $request->user();
        if ($user->isEmployee() && $leaveRequest->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $leaveRequest->load('user', 'approver');

        return response()->json([
            'data' => new LeaveRequestResource($leaveRequest),
        ], 200);
    }

    /**
     * Approve a leave request
     */
    public function approve(LeaveRequest $leaveRequest, ApproveLeaveRequestRequest $request): JsonResponse
    {
        try {
            $dto = new ApproveLeaveRequestDTO(
                leave_request_id: $leaveRequest->id,
                approved_by: $request->user()->id,
                notes: $request->input('notes'),
            );

            $leaveRequest = $this->leaveRequestService->approve($dto);
            $leaveRequest->load('user', 'approver');

            return response()->json([
                'message' => 'Pengajuan cuti berhasil disetujui',
                'data' => new LeaveRequestResource($leaveRequest),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyetujui pengajuan cuti',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reject a leave request
     */
    public function reject(LeaveRequest $leaveRequest, RejectLeaveRequestRequest $request): JsonResponse
    {
        try {
            $dto = new RejectLeaveRequestDTO(
                leave_request_id: $leaveRequest->id,
                rejected_by: $request->user()->id,
                reason: $request->input('reason'),
            );

            $leaveRequest = $this->leaveRequestService->reject($dto);
            $leaveRequest->load('user', 'approver');

            return response()->json([
                'message' => 'Pengajuan cuti berhasil ditolak',
                'data' => new LeaveRequestResource($leaveRequest),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menolak pengajuan cuti',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get user's leave balance
     */
    public function getBalance(Request $request): JsonResponse
    {
        $user = $request->user();
        $year = $request->query('year', now()->year);

        $leaveBalance = $this->leaveRequestService->getBalance($user->id, (int)$year);

        if (!$leaveBalance) {
            return response()->json([
                'message' => 'Leave balance not found',
            ], 404);
        }

        return response()->json([
            'data' => new LeaveBalanceResource($leaveBalance),
        ], 200);
    }
}
