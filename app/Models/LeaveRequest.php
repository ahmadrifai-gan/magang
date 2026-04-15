<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'attachment_path',
        'status',
        'approved_by',
        'approval_notes',
        'approved_at',
    ];

    protected $casts = [
        'status' => LeaveStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['days_requested'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get number of days requested
     */
    public function getDaysRequestedAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Approve the leave request
     */
    public function approve(int $approvedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => LeaveStatus::APPROVED,
            'approved_by' => $approvedBy,
            'approval_notes' => $notes,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the leave request
     */
    public function reject(int $approvedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => LeaveStatus::REJECTED,
            'approved_by' => $approvedBy,
            'approval_notes' => $notes,
            'approved_at' => now(),
        ]);
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === LeaveStatus::PENDING;
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === LeaveStatus::APPROVED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === LeaveStatus::REJECTED;
    }

    /**
     * Get attachment URL
     */
    public function getAttachmentUrl(): ?string
    {
        return $this->attachment_path ? url('storage/' . $this->attachment_path) : null;
    }
}
