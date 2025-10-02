<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SpouseSheetImport implements OnEachRow, WithHeadingRow
{
    public static $data = [];
    protected static $stop = false;

    public function onRow(Row $row)
    {
        if (self::$stop) return;

        $r = $row->toArray();

        if (collect($r)->filter()->isEmpty()) {
            self::$stop = true;
            return;
        }

        self::$data[] = [
            'name' => $r['name'] ?? null,
            'ssn_num' => $r['ssn_num'] ?? null,
            'spouse_name' => $r['spouse_name'] ?? null,
            'spouse_status' => $r['spouse_status'] ?? null,
            'spouse_ic' => $r['spouse_ic'] ?? null,
            'spouse_tax' => $r['spouse_tax'] ?? null,
        ];
    }
}

