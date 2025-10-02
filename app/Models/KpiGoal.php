<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiGoal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kpi_goals';

    /**
     * The attributes that are mass assignable.
     * We need all goal-related fields and the foreign key.
     *
     * @var array
     */
    protected $fillable = [
        'kpi_id',
        'goal',
        'measurement',
        'weightage',
    ];

    /**
     * Get the KPI that owns the goal.
     */
    public function kpi()
    {
        return $this->belongsTo(Kpi::class);
    }

    public function trackings()
    {
        return $this->hasMany(KpiGoalTracking::class);
    }
}
