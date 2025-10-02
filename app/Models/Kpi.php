<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'appraisal_id',
        'department_id',
        'unit',
        'manager_id',
        'weightage',
        'year',
        'status',
        'accepted',
        'accepted_at',
        'total_weightage',
        'assigned_to_staff_id',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to_staff_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class, 'appraisal_id');
    }

    public function goals()
    {
        return $this->hasMany(KpiGoal::class);
    }
}
