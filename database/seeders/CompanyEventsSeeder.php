<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyEvent;
use Carbon\Carbon;

class CompanyEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyEvent::truncate();

        $events = [
            [
                'title' => 'Annual Town Hall Meeting',
                'start_date' => Carbon::create(2025, 10, 15)->toDateString(),
                'end_date' => Carbon::create(2025, 10, 15)->toDateString(),
                'comments' => 'A mandatory meeting for all staff.',
            ],
            [
                'title' => 'Year-End Celebration',
                'start_date' => Carbon::create(2025, 12, 10)->toDateString(),
                'end_date' => Carbon::create(2025, 12, 10)->toDateString(),
                'comments' => 'Casual gathering to celebrate the end of the year.',
            ],
            [
                'title' => 'Q3 Performance Review',
                'start_date' => Carbon::create(2025, 9, 25)->toDateString(),
                'end_date' => Carbon::create(2025, 9, 26)->toDateString(),
                'comments' => 'Performance reviews for all departments.',
            ],
            [
                'title' => 'New Employee Onboarding Session',
                'start_date' => Carbon::create(2025, 11, 5)->toDateString(),
                'end_date' => Carbon::create(2025, 11, 5)->toDateString(),
                'comments' => 'Welcome new hires to the company.',
            ],
            [
                'title' => 'Christmas Dinner',
                'start_date' => Carbon::create(2025, 12, 20)->toDateString(),
                'end_date' => Carbon::create(2025, 12, 20)->toDateString(),
                'comments' => 'Annual Christmas celebration for all employees.',
            ],
            [
                'title' => 'Annual Company Retreat',
                'start_date' => Carbon::create(2026, 3, 15)->toDateString(),
                'end_date' => Carbon::create(2026, 3, 17)->toDateString(),
                'comments' => 'Team building retreat for all staff.',
            ],
            [
                'title' => 'Sales Kick-off Meeting',
                'start_date' => Carbon::create(2026, 1, 10)->toDateString(),
                'end_date' => Carbon::create(2026, 1, 10)->toDateString(),
                'comments' => 'Setting sales goals for the new year.',
            ],
        ];

        foreach ($events as $event) {
            CompanyEvent::create($event);
        }
    }
}