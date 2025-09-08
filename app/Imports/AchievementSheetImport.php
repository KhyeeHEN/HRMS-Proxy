<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AchievementSheetImport implements ToCollection, WithHeadingRow
{
    public static $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) break;

            self::$data[] = [
                'qualification' => $row['qualification'] ?? null,
                'experience'    => $row['experience'] ?? null,
            ];
        }
    }
}
