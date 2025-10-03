<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppraisalUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // --- Section 1: KPI Goal Scores (1-100 scale) ---
            'goal_scores' => 'nullable|array',
            'goal_scores.*.staff_score' => 'nullable|numeric|min:1|max:100',
            'goal_scores.*.appraiser_1_score' => 'nullable|numeric|min:1|max:100',
            'goal_scores.*.appraiser_2_score' => 'nullable|numeric|min:1|max:100',

            // --- Section 2: Competency Scores (0-10 scale) ---
            'comp_scores' => 'nullable|array',
            'comp_scores.*.staff_score' => 'nullable|numeric|min:0|max:10',
            'comp_scores.*.appraiser_1_score' => 'nullable|numeric|min:0|max:10',
            'comp_scores.*.appraiser_2_score' => 'nullable|numeric|min:0|max:10',

            // --- Section 2/6: Comments ---
            'kpi_goal_comments' => 'nullable|string|max:5000',
            'org_core_competency_comments' => 'nullable|string|max:5000',
            'job_family_competency_comments' => 'nullable|string|max:5000',
            'appraisee_comments' => 'nullable|string|max:5000',
            'appraiser_1_comments' => 'nullable|string|max:5000',
            'appraiser_2_comments' => 'nullable|string|max:5000',

            // --- Section 4: Staff Contribution ---
            'special_projects_comment' => 'nullable|string|max:5000',
            'major_achievements_comment' => 'nullable|string|max:5000',

            // --- Section 5: Career Development ---
            'promotion_potential_now' => 'nullable|in:High,Low,Not Ready',
            'promotion_potential_1_2_years' => 'nullable|in:High,Low,Not Ready',
            'promotion_potential_after_2_years' => 'nullable|in:High,Low,Not Ready',

            // Existing comments for each period
            'promotion_now_comment' => 'nullable|string|max:5000',
            'promotion_1_2_years_comment' => 'nullable|string|max:5000',
            'promotion_after_2_years_comment' => 'nullable|string|max:5000',
            'personal_growth_comment' => 'nullable|string|max:5000',

            // --- Action/Status ---
            // --- ADDED: Action Validation ---
            'action' => [
                'required',
                'string',
                // Must be one of the expected values for saving or submission
                Rule::in(['draft', 'appraisee_submit', 'appraiser1_submit', 'appraiser2_submit']),
            ],

            // --- ADDED: Signature Fields Validation (Optional but Recommended) ---
            'appraisee_signed' => 'nullable|boolean',
            'appraiser1_signed' => 'nullable|boolean',
            'appraiser2_signed' => 'nullable|boolean',
        ];
    }
}
