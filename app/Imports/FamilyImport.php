<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Family;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;

class FamilyImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        // Check if the row is empty by verifying the essential fields
        if (empty($row['name'])) {
            // Log the skipped row
            Log::info('Skipped empty row: ', $row);
            return null;
        }

        // Use the full SSN number to find the employee
        $ssnNum = $row['ssn_num'];

        // Find the employee with the matching SSN number
        $employee = Employee::where('ssn_num', $ssnNum)->first();

        if ($employee) {
            // Check if a family record with this SSN number already exists in the family table
            $existingFamily = Family::where('ssn_num', $ssnNum)->first();

            if ($existingFamily) {
                // Log that the family record already exists and skip adding new data
                Log::info('Family record with SSN already exists: ' . $ssnNum);
                return null;
            }

            // If no family record exists, create a new one
            $family = new Family([
                'name' => $row['name'],
                'ssn_num' => $row['ssn_num'],
                'spouse_name' => $row['spouse_name'],
                'spouse_status' => $row['spouse_status'],
                'spouse_ic' => $row['spouse_ic'],
                'spouse_tax' => $row['spouse_tax'],
                'noc_under' => $row['noc_under'],
                'tax_under' => $row['tax_under'],
                'noc_above' => $row['noc_above'],
                'tax_above' => $row['tax_above'],
                'child1' => $row['child1'],
                'child2' => $row['child2'],
                'child3' => $row['child3'],
                'child4' => $row['child4'],
                'child5' => $row['child5'],
                'child6' => $row['child6'],
                'child7' => $row['child7'],
                'child8' => $row['child8'],
                'child9' => $row['child9'],
                'child10' => $row['child10'],
                'contact1_name' => $row['contact1_name'],
                'contact1_no' => $row['contact1_no'],
                'contact1_rel' => $row['contact1_rel'],
                'contact1_add' => $row['contact1_add'],
                'contact2_name' => $row['contact2_name'],
                'contact2_no' => $row['contact2_no'],
                'contact2_rel' => $row['contact2_rel'],
                'contact2_add' => $row['contact2_add'],
                'contact3_name' => $row['contact3_name'],
                'contact3_no' => $row['contact3_no'],
                'contact3_rel' => $row['contact3_rel'],
                'contact3_add' => $row['contact3_add'],
            ]);

            $family->save();

            // Update the employee's family_id to point to the newly created family record
            $employee->family = $family->id;
            $employee->save();

            Log::info('Created new family record for SSN: ' . $ssnNum);

            return $family;
        } else {
            // Optionally log an error if no matching employee is found
            Log::warning('No matching employee found for SSN: ' . $ssnNum);
            return null; // Skip this row
        }
    }
}
