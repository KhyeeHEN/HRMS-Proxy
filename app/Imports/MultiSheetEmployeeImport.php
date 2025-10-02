<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Events\AfterImport;

class MultiSheetEmployeeImport implements WithMultipleSheets, WithEvents
{
    use Importable;

    private $personalImport;
    private $workImport;
    private $achievementImport;

    public function __construct()
    {
        $this->personalImport = new PersonalSheetImport();
        $this->workImport = new WorkSheetImport();
        $this->achievementImport = new AchievementSheetImport();
    }

    public function sheets(): array
    {
        return [
            'Personal' => $this->personalImport,
            'Work' => $this->workImport,
            'Achievement' => $this->achievementImport,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                $this->afterImport();
            }
        ];
    }

    public function afterImport()
    {
        $personalData = $this->personalImport::$data;
        $workData = $this->workImport::$data;
        $achievementData = $this->achievementImport::$data;

        $maxIndex = max(
            count($personalData),
            count($workData),
            count($achievementData)
        );

        for ($i = 0; $i < $maxIndex; $i++) {
            $personal = $personalData[$i] ?? [];
            $work = $workData[$i] ?? [];
            $ach = $achievementData[$i] ?? [];

            $merged = array_merge($personal, $work, $ach);

            if (empty(array_filter($merged))) {
                continue;
            }

            $dateFields = ['joined_date', 'termination_date', 'award_date', 'birthday', 'hire_date'];
            foreach ($dateFields as $field) {
                if (!empty($merged[$field])) {
                    $merged[$field] = self::transformDate($merged[$field]);
                }
            }

            $status = (!empty($merged['termination_date'])) ? 0 : 1;
            $ssn = $merged['ssn_num'] ?? null;
            $newEmployeeId = $merged['employee_id'] ?? null;

            $existing = $ssn ? Employee::where('ssn_num', $ssn)->orderBy('id', 'desc')->first() : null;

            if ($existing && $existing->termination_date && $existing->employee_id != $newEmployeeId) {
                $employee = new Employee();
            } else {
                $employee = $existing ?? new Employee();
            }

            $fields = array_keys($merged); // semua key dari Excel
            $dirty = false;

            foreach ($fields as $field) {
                if (array_key_exists($field, $merged)) {
                    if (in_array($field, $dateFields)) {
                        $val = $merged[$field] ?? null;

                        if (!empty($val)) {
                            $val = is_a($val, Carbon::class) ? $val : self::transformDate($val);
                        } else {
                            // Only joined_date gets current date if empty
                            $val = $field === 'joined_date' ? now() : null;
                        }
                    } else {
                        $val = $merged[$field];
                    }

                    if ($employee->$field != $val) {
                        $employee->$field = $val;
                        $dirty = true;
                    }
                }
            }

            if ($employee->status != $status) {
                $employee->status = $status;
                $dirty = true;
            }

            if ($dirty) {
                $employee->save();
            }

            if ($ssn) {
                $firstName = $employee->first_name ?? '';
                $last4 = substr(preg_replace('/\D/', '', $ssn), -4);
                $newFolderName = $last4 . $firstName;
                $basePath = storage_path('app/employees');
                $newPath = $basePath . '/' . $newFolderName;

                if (!File::exists($basePath)) {
                    File::makeDirectory($basePath, 0755, true);
                }

                if (!$employee->wasRecentlyCreated && $employee->isDirty('first_name')) {
                    $oldFirst = $employee->getOriginal('first_name');
                    $oldPath = $basePath . '/' . $last4 . $oldFirst;
                    if (File::exists($oldPath)) {
                        File::move($oldPath, $newPath);
                    }
                }

                if (!File::exists($newPath)) {
                    File::makeDirectory($newPath, 0755, true);
                }
            }
        }
    }

    protected static function transformDate($value)
    {
        if (is_numeric($value)) {
            try {
                return Carbon::instance(PhpSpreadsheetDate::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
