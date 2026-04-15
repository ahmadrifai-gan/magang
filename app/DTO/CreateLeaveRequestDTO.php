<?php

namespace App\DTO;

use DateTime;

class CreateLeaveRequestDTO
{
    public function __construct(
        public int $user_id,
        public DateTime $start_date,
        public DateTime $end_date,
        public string $reason,
        public ?string $attachment_path = null,
    ) {}

    /**
     * Calculate number of days
     */
    public function getDaysRequested(): int
    {
        return $this->start_date->diff($this->end_date)->days + 1;
    }

    /**
     * Validate that end date is after start date
     */
    public function isValid(): bool
    {
        return $this->end_date >= $this->start_date;
    }
}
