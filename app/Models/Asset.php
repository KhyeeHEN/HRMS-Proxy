<?php
// app/Models/Asset.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\AssetAssignment;
use App\Models\AssetCategory;

class Asset extends Model
{
    protected $table = 'company_assets';

    protected $fillable = [
        'asset_id', 'asset_name', 'user', 'department', 'type', 'status', 'model', 'sn_no',
        'cpu', 'ram', 'hdd','hdd_bal','hdd2','hdd2_bal', 'ssd','ssd_bal', 'os', 'os_key', 'office', 'office_key',
        'office_login', 'antivirus', 'synology', 'dop', 'warranty_end', 'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function show($id)
    {
        $asset = Asset::with('employee')->findOrFail($id);
        return response()->json($asset);
    }
    public function departmentInfo()
    {
        return $this->belongsTo(JobTitle::class, 'department');
    }
    public function currentAssignment()
    {
        return $this->hasOne(AssetAssignment::class, 'asset_id');
    }
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'type');
    }
}