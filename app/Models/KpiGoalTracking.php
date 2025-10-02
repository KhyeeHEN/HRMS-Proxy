<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiGoalTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_goal_id',
        'user_id',
        'achievement',
        'manager_comment',
    ];

    // The goal this entry is tracking
    public function goal()
    {
        return $this->belongsTo(KpiGoal::class, 'kpi_goal_id');
    }

    // The user (staff) who submitted the entry
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
