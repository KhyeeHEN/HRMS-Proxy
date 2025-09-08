<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Holiday::truncate();

        $holidays = [
            ['name' => 'New Year’s Day', 'month' => 1, 'day' => 1],
            ['name' => 'Thaipusam', 'month' => 1, 'day' => 20],
            ['name' => 'Federal Territory Day', 'month' => 2, 'day' => 1],
            ['name' => 'Chinese New Year', 'month' => 2, 'day' => 9],
            ['name' => 'Chinese New Year (2nd Day)', 'month' => 2, 'day' => 10],
            ['name' => 'Nuzul Al-Quran', 'month' => 3, 'day' => 29],
            ['name' => 'Labour Day', 'month' => 5, 'day' => 1],
            ['name' => 'Wesak Day', 'month' => 5, 'day' => 12],
            ['name' => 'Hari Raya Aidilfitri', 'month' => 5, 'day' => 30],
            ['name' => 'Hari Raya Aidilfitri (2nd Day)', 'month' => 5, 'day' => 31],
            ['name' => 'King’s Birthday', 'month' => 6, 'day' => 2],
            ['name' => 'Hari Raya Haji', 'month' => 6, 'day' => 16],
            ['name' => 'Awal Muharram (Maal Hijrah)', 'month' => 7, 'day' => 6],
            ['name' => 'National Day', 'month' => 8, 'day' => 31],
            ['name' => 'Malaysia Day', 'month' => 9, 'day' => 16],
            ['name' => 'Prophet Muhammad’s Birthday', 'month' => 10, 'day' => 5],
            ['name' => 'Deepavali', 'month' => 10, 'day' => 21],
            ['name' => 'Christmas Day', 'month' => 12, 'day' => 25],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
