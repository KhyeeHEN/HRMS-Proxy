<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Core Links & Metadata
        'appraisee_id',
        'appraiser_1_id',
        'appraiser_2_id',
        'status',
        'year',
        'review_period_start',
        'review_period_end',

        // Section 1-3 Scoring
        'section_1_score',
        'section_2a_score',
        'section_2b_score',
        'section_3_overall_score', // Sum of 1, 2a, 2b

        // Section 1 & 2 Comments
        'kpi_goal_comments', // <-- ADDED
        'org_core_competency_comments',
        'job_family_competency_comments',

        // Section 4: Staff Contribution
        'special_projects_comment',
        'major_achievements_comment',

        // Section 5: Career Development & Personal Growth
        'promotion_potential_now',
        'promotion_potential_1_2_years',
        'promotion_potential_after_2_years',
        'promotion_now_comment',
        'promotion_1_2_years_comment',
        'promotion_after_2_years_comment',
        'personal_growth_comment',

        // Section 6: Comments & Acknowledgement
        'appraisee_comments',
        'appraisee_signed_at',
        'appraiser_1_comments',
        'appraiser_1_signed_at',
        'appraiser_2_comments',
        'appraiser_2_signed_at',
        'appraisee_signed',
        'appraiser1_signed',
        'appraiser2_signed',
        'appraisee_submitted',
        'appraiser1_submitted',
        'appraiser2_submitted',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'appraisee_signed_at' => 'datetime',
        'appraiser_1_signed_at' => 'datetime',
        'appraiser_2_signed_at' => 'datetime',
        'review_period_start' => 'date',
        'review_period_end' => 'date',
    ];

    // --- RELATIONSHIPS ---

    /**
     * Get the staff member being appraised.
     */
    public function appraisee()
    {
        return $this->belongsTo(User::class, 'appraisee_id');
    }

    /**
     * Get the primary manager/appraiser.
     */
    public function appraiser1()
    {
        return $this->belongsTo(User::class, 'appraiser_1_id');
    }

    /**
     * Get the secondary manager/appraiser (e.g., Department Head).
     */
    public function appraiser2()
    {
        return $this->belongsTo(User::class, 'appraiser_2_id');
    }

    /**
     * Get the KPI associated with this appraisal.
     */
    public function kpi()
    {
        // Assumes the 'kpis' table has an 'appraisal_id' foreign key
        return $this->hasOne(Kpi::class, 'appraisal_id');
    }

    public function goalScores()
    {
        return $this->hasMany(AppraisalGoalScore::class);
    }

    public function competencyScores()
    {
        return $this->hasMany(AppraisalCompetencyScore::class);
    }
}
