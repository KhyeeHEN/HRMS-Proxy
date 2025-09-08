<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmergencySheetImport implements OnEachRow, WithHeadingRow
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
            'contact1_name' => $r['contact1_name'] ?? null,
            'contact1_no' => $r['contact1_no'] ?? null,
            'contact1_rel' => $r['contact1_rel'] ?? null,
            'contact1_add' => $r['contact1_add'] ?? null,

            'contact2_name' => $r['contact2_name'] ?? null,
            'contact2_no' => $r['contact2_no'] ?? null,
            'contact2_rel' => $r['contact2_rel'] ?? null,
            'contact2_add' => $r['contact2_add'] ?? null,

            'contact3_name' => $r['contact3_name'] ?? null,
            'contact3_no' => $r['contact3_no'] ?? null,
            'contact3_rel' => $r['contact3_rel'] ?? null,
            'contact3_add' => $r['contact3_add'] ?? null,
        ];
    }
}
