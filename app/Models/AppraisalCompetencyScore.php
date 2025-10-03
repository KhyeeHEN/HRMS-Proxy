<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppraisalCompetencyScore extends Model
{
    use HasFactory;

    // Define the competency attributes for Section 2a and 2b
    public const ATTRIBUTES_2A = [
        'quality_focused' => 'Quality Focused',
        'communication_skills' => 'Communication Skills',
        'teamwork' => 'Teamwork/Cooperation',
        'work_quantity_timeliness' => 'Work Quantity & Timeliness',
        'customer_focused' => 'Customer Focused',
        'integrity' => 'Integrity',
        'passion' => 'Passion',
    ];

    public const ATTRIBUTES_2B = [
        'acceptance_of_instruction' => 'Acceptance of Instruction',
        'time_keeping_attendance' => 'Time Keeping/Attendance',
        'knowledge_of_job' => 'Knowledge of Job',
        'effort_diligence' => 'Effort & Diligence',
        'problem_solving' => 'Problem Solving Skill',
        'organising_planning' => 'Organising & Planning',
        'performance_under_pressure' => 'Performance under Pressure',
        'continuous_improvement' => 'Continuous Improvement',
        'reliability_decision_making' => 'Reliability. Decision-Making Skills',
    ];

    protected $fillable = [
        'appraisal_id',
        'section_type',
        'attribute_key',
        'staff_score', // <-- ADDED
        'appraiser_1_score',
        'appraiser_2_score',
        'average_score',
        'weighted_score',
    ];
}