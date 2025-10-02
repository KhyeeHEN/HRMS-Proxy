<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;

class EmployeesImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected $ssnFolders = [];  // Store SSN and folder names

    public function model(array $row)
    {
        // Check if the row is empty by verifying essential fields
        if (
            empty($row['employee_id']) &&
            empty($row['first_name']) &&
            empty($row['last_name']) &&
            empty($row['birthday']) &&
            empty($row['gender'])
        ) {
            Log::info('Skipped empty row: ', $row);
            return null;
        }

        // Convert date formats if necessary
        $birthday = $this->parseDate($row['birthday']);
        $joined_date = $this->parseDate($row['joined_date']);
        $newTerminationDate = $this->parseDate($row['termination_date']);

        // Use the full SSN number to find the employee
        $ssnNum = $row['ssn_num'];
        $employeeId = $row['employee_id'];

        // Find the employee with the matching SSN number
        $employee = Employee::where('ssn_num', $ssnNum)->first();

        // Generate folder path based on last 4 digits of SSN and first name
        $lastSixDigits = substr($ssnNum, -7);
        $firstName = preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $row['first_name']));
        $folderName = $lastSixDigits . '-' . $firstName;

        // Add the SSN and folder name to the ssnFolders array
        $this->ssnFolders[] = [
            'ssn' => $ssnNum,
            'folder' => $folderName
        ];

        // If an employee record exists
        if ($employee) {
            $changes = false;
            $employmentIdChanged = false;

            $fieldsToCheck = [
                'employee_id',
                'first_name',
                'last_name',
                'nationality',
                'birthday',
                'gender',
                'marital_status',
                'ssn_num',
                'employment_status',
                'job_title',
                'pay_grade',
                'work_station_id',
                'branch',
                'address1',
                'address2',
                'city',
                'country',
                'state',
                'postal_code',
                'home_phone',
                'mobile_phone',
                'work_phone',
                'work_email',
                'private_email',
                'joined_date',
                'supervisor',
                'indirect_supervisors',
                'company',
                'department',
                'notes',
                'ethnicity',
                'immigration_status',
                'epf_no',
                'socso',
                'lhdn_no',
                'family',
                'qualification',
                'experience',
                'folder'
            ];

            foreach ($fieldsToCheck as $field) {
                if (in_array($field, ['birthday', 'driving_license_exp_date', 'joined_date', 'confirmation_date'])) {
                    $newValue = $row[$field] ? $this->parseDate($row[$field]) : null;
                } elseif ($field === 'termination_date') {
                    $newValue = $row[$field] !== null ? $newTerminationDate : $employee->termination_date;
                } elseif ($field === 'family') {
                    $newValue = $row[$field] !== null ? $row[$field] : $employee->$field;
                } else {
                    $newValue = $row[$field];
                }

                if ($employee->$field != $newValue) {
                    $changes = true;
                    if ($field === 'employee_id') {
                        $employmentIdChanged = true;
                    }
                    $employee->$field = $newValue;
                }
            }

            // Set status based on termination_date
            $employee->status = $employee->termination_date ? 'terminated' : 'active';

            if ($employee->termination_date) {
                $existingNewEmployee = Employee::where('ssn_num', $ssnNum)
                    ->where('employee_id', $employeeId)
                    ->first();

                if ($employmentIdChanged && !$existingNewEmployee) {
                    Log::info('Creating new employee record for SSN: ' . $ssnNum);
                    return new Employee([
                        'employee_id' => $row['employee_id'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'nationality' => $row['nationality'],
                        'birthday' => $birthday,
                        'gender' => $row['gender'],
                        'marital_status' => $row['marital_status'],
                        'ssn_num' => $row['ssn_num'],
                        'employment_status' => $row['employment_status'],
                        'job_title' => $row['job_title'],
                        'pay_grade' => $row['pay_grade'],
                        'work_station_id' => $row['work_station_id'],
                        'branch' => $row['branch'],
                        'address1' => $row['address1'],
                        'address2' => $row['address2'],
                        'city' => $row['city'],
                        'country' => $row['country'],
                        'state' => $row['state'],
                        'postal_code' => $row['postal_code'],
                        'home_phone' => $row['home_phone'],
                        'mobile_phone' => $row['mobile_phone'],
                        'work_phone' => $row['work_phone'],
                        'work_email' => $row['work_email'],
                        'private_email' => $row['private_email'],
                        'joined_date' => $joined_date,
                        'supervisor' => $row['supervisor'],
                        'indirect_supervisors' => $row['indirect_supervisors'],
                        'company' => $row['company'],
                        'department' => $row['department'],
                        'termination_date' => $newTerminationDate,
                        'status' => $newTerminationDate ? 'terminated' : 'active',
                        'notes' => $row['notes'],
                        'ethnicity' => $row['ethnicity'],
                        'immigration_status' => $row['immigration_status'],
                        'epf_no' => $row['epf_no'],
                        'socso' => $row['socso'],
                        'lhdn_no' => $row['lhdn_no'],
                        'family' => $row['family'],
                        'qualification' => $row['qualification'],
                        'experience' => $row['experience'],
                        'folder' => $folderName,  // Save folder name in the database
                    ]);
                } else {
                    Log::info('No employment_status change detected or duplicate found for employee with SSN: ' . $ssnNum);
                }
            } else {
                if ($changes) {
                    Log::info('Updating existing employee record for SSN: ' . $ssnNum);
                    $employee->save();
                } else {
                    Log::info('No changes detected for employee record with SSN: ' . $ssnNum);
                }
                return $employee;
            }
        } else {
            Log::info('Creating new employee record for SSN: ' . $ssnNum);
            return new Employee([
                'employee_id' => $row['employee_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'nationality' => $row['nationality'],
                'birthday' => $birthday,
                'gender' => $row['gender'],
                'marital_status' => $row['marital_status'],
                'ssn_num' => $row['ssn_num'],
                'employment_status' => $row['employment_status'],
                'job_title' => $row['job_title'],
                'pay_grade' => $row['pay_grade'],
                'work_station_id' => $row['work_station_id'],
                'branch' => $row['branch'],
                'address1' => $row['address1'],
                'address2' => $row['address2'],
                'city' => $row['city'],
                'country' => $row['country'],
                'state' => $row['state'],
                'postal_code' => $row['postal_code'],
                'home_phone' => $row['home_phone'],
                'mobile_phone' => $row['mobile_phone'],
                'work_phone' => $row['work_phone'],
                'work_email' => $row['work_email'],
                'private_email' => $row['private_email'],
                'joined_date' => $joined_date,
                'supervisor' => $row['supervisor'],
                'indirect_supervisors' => $row['indirect_supervisors'],
                'company' => $row['company'],
                'department' => $row['department'],
                'termination_date' => $newTerminationDate,
                'status' => $newTerminationDate ? 'terminated' : 'active',
                'notes' => $row['notes'],
                'ethnicity' => $row['ethnicity'],
                'immigration_status' => $row['immigration_status'],
                'epf_no' => $row['epf_no'],
                'socso' => $row['socso'],
                'lhdn_no' => $row['lhdn_no'],
                'family' => $row['family'],
                'qualification' => $row['qualification'],
                'experience' => $row['experience'],
                'folder' => $folderName  // Save folder name in the database
            ]);
        }
    }

    // Handle folder creation after the import process and update database
    public function onAfterImport()
    {
        foreach ($this->ssnFolders as $data) {
            $folderPath = public_path('emp/' . $data['folder']);

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
                Log::info('Created folder for employee: ' . $data['folder']);
            }

            // Update the database with the full path
            $employee = Employee::where('ssn_num', $data['ssn'])->first();
            if ($employee) {
                $employee->folder = 'emp/' . $data['folder'];
                $employee->save();
                Log::info('Updated folder path in database for employee with SSN: ' . $data['ssn']);
            } else {
                Log::warning('Employee not found for SSN: ' . $data['ssn']);
            }
        }
    }
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
    
        try {
            // If the value is numeric, assume it's an Excel date
            if (is_numeric($value)) {
                return PhpSpreadsheetDate::excelToDateTimeObject($value)->format('Y-m-d');
            } 
            
            // If it's a string, we need to handle it more carefully
            $cleanValue = preg_split('/\s*\(/', trim($value))[0]; // Get only the date part
            return Carbon::createFromFormat('d-M-Y', $cleanValue)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Failed to parse date: ' . $value . ' - ' . $e->getMessage());
            return null;
        }
    }
}
