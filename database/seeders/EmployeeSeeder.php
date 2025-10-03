<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeesToCreate = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'department_id' => 4, // Software Development
                'unit' => 'DevOps',
                'gender' => 'Male',
                'work_email' => 'staff.one@example.com'
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'department_id' => 5, // Sales
                'unit' => 'B2B Sales',
                'gender' => 'Female',
                'work_email' => 'staff.two@example.com'
            ],
            [
                'first_name' => 'Peter',
                'last_name' => 'Jones',
                'department_id' => 1, // Management
                'unit' => 'Operations',
                'gender' => 'Male',
                'work_email' => 'staff.three@example.com'
            ],
        ];

        foreach ($employeesToCreate as $employee) {
            DB::table('employees')->insert([
                'employee_id' => 'EMP-' . Str::upper(Str::random(5)),
                'first_name' => $employee['first_name'],
                'last_name' => $employee['last_name'],
                'gender' => $employee['gender'],
                'department' => $employee['department_id'],
                'unit' => $employee['unit'],
                'joined_date' => Carbon::now()->subDays(rand(100, 1000)),
                'mobile_phone' => '01' . rand(0, 9) . '-' . rand(1000, 9999) . rand(1000, 9999),
                'work_email' => $employee['work_email'],
                'status' => 'Active',
            ]);
        }
    }
}