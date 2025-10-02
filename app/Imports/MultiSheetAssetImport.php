<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Events\AfterImport;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;

class MultiSheetAssetImport implements WithMultipleSheets, WithEvents
{
    use Importable;

    private $mainSheet;

    public function __construct()
    {
        $this->mainSheet = new AssetSheetImport();
    }

    public function sheets(): array
    {
        return [
            'ASSET DATABASE' => $this->mainSheet,
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
        foreach ($this->mainSheet::$data as $row) {
            if (!array_filter($row)) continue;

            $row = array_map('trim', $row);
            $row['dop'] = self::transformDate($row['dop'] ?? null);
            $row['warranty_end'] = self::transformDate($row['warranty_end'] ?? null);

            $existing = !empty($row['sn_no'])
                ? Asset::where('sn_no', $row['sn_no'])->first()
                : null;

            $asset = $existing ?? new Asset();

            $fields = [
                'asset_id', 'asset_name', 'employee_id', 'department', 'type', 'status', 'model', 'sn_no', 'dop', 'warranty_end', 'remarks',
                'cpu', 'ram', 'hdd', 'hdd_bal', 'hdd2', 'hdd2_bal', 'ssd', 'ssd_bal', 'os', 'os_key', 'office', 'office_key',
                'office_login', 'antivirus', 'synology'
            ];

            $dirty = false;

            foreach ($fields as $field) {
                $val = $row[$field] ?? null;
                if ($asset->$field != $val) {
                    $asset->$field = $val;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $asset->save();
            }
        }
    }

    protected static function transformDate($value)
    {
        if (is_numeric($value) && (int)$value <= 3000) {
            return (int)$value;
        }

        if (is_numeric($value)) {
            try {
                return PhpSpreadsheetDate::excelToDateTimeObject($value)->format('Y');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($value)->format('Y');
        } catch (\Exception $e) {
            return null;
        }
    }
}
