<?php

namespace App\Imports;

use App\Imports\SpouseSheetImport;
use App\Imports\ChildSheetImport;
use App\Imports\EmergencySheetImport;
use App\Models\Family;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Events\AfterImport;

class MultiSheetFamilyImport implements WithMultipleSheets, WithEvents
{
    use Importable;

    private $spouseImport;
    private $childImport;
    private $emergencyImport;

    public function __construct()
    {
        $this->spouseImport = new SpouseSheetImport();
        $this->childImport = new ChildSheetImport();
        $this->emergencyImport = new EmergencySheetImport();
    }

    public function sheets(): array
    {
        return [
            'Spouse' => $this->spouseImport,
            'Child' => $this->childImport,
            'Emergency' => $this->emergencyImport,
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
        $spouseData = $this->spouseImport::$data;
        $childData = $this->childImport::$data;
        $emergencyData = $this->emergencyImport::$data;

        $max = max(count($spouseData), count($childData), count($emergencyData));

        for ($i = 0; $i < $max; $i++) {
            $spouse = $spouseData[$i] ?? [];
            $child = $childData[$i] ?? [];
            $emergency = $emergencyData[$i] ?? [];

            $merged = array_merge($spouse, $child, $emergency);

            if (empty(array_filter($merged))) {
                continue;
            }

            $ssn = $merged['ssn_num'] ?? null;

            if ($ssn) {
                $family = Family::updateOrCreate(
                    ['ssn_num' => $ssn],
                    $merged
                );

                if ($ssn && $family) {
                    \App\Models\Employee::where('ssn_num', $ssn)->update(['family' => $family->id]);
                }
            }
        }
    }
}
