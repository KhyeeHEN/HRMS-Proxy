<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChildSheetImport implements OnEachRow, WithHeadingRow
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
            'noc_under' => $r['noc_under'] ?? null,
            'tax_under' => $r['tax_under'] ?? null,
            'noc_above' => $r['noc_above'] ?? null,
            'tax_above' => $r['tax_above'] ?? null,
            'child1' => $r['child1'] ?? null,
            'child2' => $r['child2'] ?? null,
            'child3' => $r['child3'] ?? null,
            'child4' => $r['child4'] ?? null,
            'child5' => $r['child5'] ?? null,
            'child6' => $r['child6'] ?? null,
            'child7' => $r['child7'] ?? null,
            'child8' => $r['child8'] ?? null,
            'child9' => $r['child9'] ?? null,
            'child10' => $r['child10'] ?? null,
        ];
    }
}
