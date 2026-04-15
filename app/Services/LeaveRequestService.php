<?php

namespace App\Services;

use App\DTO\ApproveLeaveRequestDTO;
use App\DTO\CreateLeaveRequestDTO;
use App\DTO\RejectLeaveRequestDTO;
use App\Enums\LeaveStatus;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class LeaveRequestService
{
    /**
     * Create a new leave request
     */
    public function create(CreateLeaveRequestDTO $dto): LeaveRequest
    {
        return DB::transaction(function () use ($dto) {
            // Validate days requested
            if (!$dto->isValid()) {
                throw new Exception('End date must be after or equal to start date');
            }

            $daysRequested = $dto->getDaysRequested();

            // Check leave balance
            $leaveBalance = $this->getOrCreateBalance($dto->user_id);

            if ($leaveBalance->remaining_quota < $daysRequested) {
                throw new Exception("Insufficient leave quota. Remaining: {$leaveBalance->remaining_quota} days, Requested: {$daysRequested} days");
            }

            // Create leave request
            $leaveRequest = LeaveRequest::create([
                'user_id' => $dto->user_id,
                'start_date' => $dto->start_date,
                'end_date' => $dto->end_date,
                'reason' => $dto->reason,
                'attachment_path' => $dto->attachment_path,
                'status' => LeaveStatus::PENDING,
            ]);

            // Deduct quota temporarily (will be deducted permanently on approval)
            // Note: Quota is only deducted when approved, not when requested

            return $leaveRequest;
        });
    }

    /**
     * Approve a leave request
     */
    public function approve(ApproveLeaveRequestDTO $dto): LeaveRequest
    {
        return DB::transaction(function () use ($dto) {
            $leaveRequest = LeaveRequest::findOrFail($dto->leave_request_id);

            if (!$leaveRequest->isPending()) {
                throw new Exception('Only pending requests can be approved');
            }

            // Approve the request
            $leaveRequest->approve($dto->approved_by, $dto->notes);

            // Deduct quota
            $daysRequested = $leaveRequest->days_requested;
            $leaveBalance = LeaveBalance::where('user_id', $leaveRequest->user_id)
                ->where('year', $leaveRequest->start_date->year)
                ->first();

            if ($leaveBalance) {
                $leaveBalance->deductQuota($daysRequested);
            }

            return $leaveRequest;
        });
    }

    /**
     * Reject a leave request
     */
    public function reject(RejectLeaveRequestDTO $dto): LeaveRequest
    {
        return DB::transaction(function () use ($dto) {
            $leaveRequest = LeaveRequest::findOrFail($dto->leave_request_id);

            if (!$leaveRequest->isPending()) {
                throw new Exception('Only pending requests can be rejected');
            }

            // Reject the request
            $leaveRequest->reject($dto->rejected_by, $dto->reason);

            return $leaveRequest;
        });
    }

    /**
     * Get or create leave balance for a user in a specific year
     */
    public function getOrCreateBalance(int $userId, int $year = null): LeaveBalance
    {
        $year = $year ?? now()->year;

        return LeaveBalance::firstOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
            ],
            [
                'total_quota' => 12,
                'used_quota' => 0,
                'remaining_quota' => 12,
            ]
        );
    }

    /**
     * Get leave balance for a user
     */
    public function getBalance(int $userId, int $year = null): ?LeaveBalance
    {
        $year = $year ?? now()->year;

        return LeaveBalance::where('user_id', $userId)
            ->where('year', $year)
            ->first();
    }

    /**
     * Get user's leave requests
     */
    public function getUserLeaveRequests(int $userId, ?string $status = null)
    {
        $query = LeaveRequest::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get all leave requests (admin only)
     */
    public function getAllLeaveRequests(?string $status = null, ?int $userId = null)
    {
        $query = LeaveRequest::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
