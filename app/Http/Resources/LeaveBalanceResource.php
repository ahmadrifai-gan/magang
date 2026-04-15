<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'year' => $this->year,
            'total_quota' => $this->total_quota,
            'used_quota' => $this->used_quota,
            'remaining_quota' => $this->remaining_quota,
            'percentage_used' => round(($this->used_quota / $this->total_quota) * 100, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
