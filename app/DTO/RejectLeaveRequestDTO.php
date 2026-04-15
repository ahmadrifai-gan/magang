<?php

namespace App\DTO;

class RejectLeaveRequestDTO
{
    public function __construct(
        public int $leave_request_id,
        public int $rejected_by,
        public string $reason,
    ) {}
}
