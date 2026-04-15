<?php

namespace Database\Seeders;

use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create employee users
        $employees = [];
        for ($i = 1; $i <= 5; $i++) {
            $employees[] = User::create([
                'name' => "Employee {$i}",
                'email' => "employee{$i}@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);
        }

        // Create leave balances for all users
        $year = now()->year;
        
        // Admin leave balance
        LeaveBalance::create([
            'user_id' => $admin->id,
            'year' => $year,
            'total_quota' => 12,
            'used_quota' => 0,
            'remaining_quota' => 12,
        ]);

        // Employees leave balances
        foreach ($employees as $employee) {
            LeaveBalance::create([
                'user_id' => $employee->id,
                'year' => $year,
                'total_quota' => 12,
                'used_quota' => 0,
                'remaining_quota' => 12,
            ]);
        }
    }
}
