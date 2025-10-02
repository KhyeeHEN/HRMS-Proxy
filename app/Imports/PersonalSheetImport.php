<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonalSheetImport implements ToCollection, WithHeadingRow
{
    public static $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) break;

            self::$data[] = [
                'first_name'     => $row['first_name'] ?? null,
                'last_name'      => $row['last_name'] ?? null,
                'nationality'    => $row['nationality'] ?? null,
                'birthday'       => $row['birthday'] ?? null,
                'gender'         => $row['gender'] ?? null,
                'marital_status' => $row['marital_status'] ?? null,
                'ssn_num'        => $row['ssn_num'] ?? null,
                'address1'       => $row['address1'] ?? null,
                'address2'       => $row['address2'] ?? null,
                'city'           => $row['city'] ?? null,
                'country'        => $row['country'] ?? null,
                'state'          => $row['state'] ?? null,
                'postal_code'    => $row['postal_code'] ?? null,
                'home_phone'     => $row['home_phone'] ?? null,
                'mobile_phone'   => $row['mobile_phone'] ?? null,
                'private_email'  => $row['private_email'] ?? null,
                'notes'          => $row['notes'] ?? null,
                'ethnicity'      => $row['ethnicity'] ?? null,
                'immigration_status' => $row['immigration_status'] ?? null,
                'family'         => $row['family'] ?? null,
                'photo'          => $row['photo'] ?? null,
                'folder'         => $row['folder'] ?? null,
            ];
        }
    }
}
