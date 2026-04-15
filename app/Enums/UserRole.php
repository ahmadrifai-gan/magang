<?php

namespace App\Enums;

enum UserRole: string
{
    case EMPLOYEE = 'employee';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::EMPLOYEE => 'Karyawan',
            self::ADMIN => 'Admin',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this === self::EMPLOYEE;
    }
}
