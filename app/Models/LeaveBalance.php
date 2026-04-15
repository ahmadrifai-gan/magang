<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'total_quota',
        'used_quota',
        'remaining_quota',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_quota' => 'integer',
        'used_quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    protected $appends = [
        'total_days',
        'used_days',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk backward compatibility
     */
    public function getTotalDaysAttribute(): int
    {
        return $this->total_quota;
    }

    /**
     * Accessor untuk backward compatibility
     */
    public function getUsedDaysAttribute(): int
    {
        return $this->used_quota;
    }

    /**
     * Decrease remaining quota
     */
    public function deductQuota(int $days): bool
    {
        if ($this->remaining_quota < $days) {
            return false;
        }

        $this->update([
            'used_quota' => $this->used_quota + $days,
            'remaining_quota' => $this->remaining_quota - $days,
        ]);

        return true;
    }

    /**
     * Restore quota (when request is rejected)
     */
    public function restoreQuota(int $days): void
    {
        $this->update([
            'used_quota' => $this->used_quota - $days,
            'remaining_quota' => $this->remaining_quota + $days,
        ]);
    }
}
