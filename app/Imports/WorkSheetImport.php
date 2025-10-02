<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WorkSheetImport implements ToCollection, WithHeadingRow
{
    public static $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) break;

            self::$data[] = [
                'employee_id'       => $row['employee_id'] ?? null,
                'company'           => $row['company'] ?? null,
                'joined_date'       => $row['joined_date'] ?? null,
                'termination_date'  => $row['termination_date'] ?? null,
                'employment_status' => $row['employment_status'] ?? null,
                'job_title'         => $row['job_title'] ?? null,
                'department'        => $row['department'] ?? null,
                'supervisor'        => $row['supervisor'] ?? null,
                'indirect_supervisors' => $row['indirect_supervisors'] ?? null,
                'work_phone'        => $row['work_phone'] ?? null,
                'work_email'        => $row['work_email'] ?? null,
                'pay_grade'         => $row['pay_grade'] ?? null,
                'work_station_id'   => $row['work_station_id'] ?? null,
                'branch'            => $row['branch'] ?? null,
                'status'            => $row['status'] ?? null,
                'epf_no'            => $row['epf_no'] ?? null,
                'socso'             => $row['socso'] ?? null,
                'lhdn_no'           => $row['lhdn_no'] ?? null,
            ];
        }
    }
}
