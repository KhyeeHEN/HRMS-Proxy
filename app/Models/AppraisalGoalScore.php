<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppraisalGoalScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'appraisal_id', 
        'kpi_goal_id', 
        'staff_score', 
        'appraiser_1_score', 
        'appraiser_2_score',
        'average_score',
        'weighted_score',
    ];
}