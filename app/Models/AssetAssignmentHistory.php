<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAssignmentHistory extends Model
{
    // 👇 Explicit table name
    protected $table = 'asset_assignment_history';

    protected $fillable = ['asset_id', 'employee_id', 'assigned_at', 'returned_at', 'remarks'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}