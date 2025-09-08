<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetSheetImport implements ToCollection, WithHeadingRow
{
    public static $data = [];

    public function collection(Collection $rows)
    {
        $emptyCounter = 0;

        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) {
                $emptyCounter++;
                if ($emptyCounter >= 2) break; // Stop after 2 consecutive blank rows (like bottom notes)
                continue;
            }

            $emptyCounter = 0;

            self::$data[] = [
                'asset_id'      => $row['asset_id'] ?? null,
                'asset_name'    => $row['asset_name'] ?? null,
                'department'    => $row['department'] ?? null,
                'type'          => $row['type'] ?? null,
                'status'        => $row['status'] ?? null,
                'model'         => $row['model'] ?? null,
                'sn_no'         => $row['sn_no'] ?? null,
                'dop'           => $row['dop'] ?? null,
                'warranty_end'  => $row['warranty_end'] ?? null,
                'remarks'       => $row['remarks'] ?? null,
                'cpu'           => $row['cpu'] ?? null,
                'ram'           => $row['ram'] ?? null,
                'hdd'           => $row['hdd'] ?? null,
                'hdd_bal'       => $row['hdd_bal'] ?? null,
                'hdd2'          => $row['hdd2'] ?? null,
                'hdd2_bal'      => $row['hdd2_bal'] ?? null,
                'ssd'           => $row['ssd'] ?? null,
                'ssd_bal'       => $row['ssd_bal'] ?? null,
                'os'            => $row['os'] ?? null,
                'os_key'        => $row['os_key'] ?? null,
                'office'        => $row['office'] ?? null,
                'office_key'    => $row['office_key'] ?? null,
                'office_login'  => $row['office_login'] ?? null,
                'antivirus'     => $row['antivirus'] ?? null,
                'synology'      => $row['synology'] ?? null,
            ];
        }
    }
}
