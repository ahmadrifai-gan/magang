<?php

namespace App\DTO;

class ApproveLeaveRequestDTO
{
    public function __construct(
        public int $leave_request_id,
        public int $approved_by,
        public ?string $notes = null,
    ) {}
}
