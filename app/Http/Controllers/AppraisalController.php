<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kpi;
use App\Models\Appraisal;
use App\Models\AppraisalGoalScore;
use App\Models\AppraisalCompetencyScore;
use App\Http\Requests\AppraisalUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Required for database transactions\
use Illuminate\Support\Carbon;

class AppraisalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $currentYear = now()->year;

        // 1. Base Query: Fetch all users who are considered staff/employees.
        // Adjust the 'access' values based on your actual system roles.
        $query = User::whereIn('access', ['Staff', 'Employee']) // Adjust roles as needed
            ->orderBy('name');

        // 2. Apply access filtering based on the logged-in user's role.
        if ($user->access === 'Staff' || $user->access === 'Employee') {
            // STAFF/EMPLOYEE ACCESS: They only see their own record in the list.
            $query->where('id', $user->id);
        }
        // ADMIN/HR/MANAGER ACCESS: They see all potential appraisees filtered by the base query.
        // If you need managers to only see staff in their department, more logic is required here.

        $potentialAppraisees = $query->get();

        // 3. Loop through the *filtered* staff list to attach their current appraisal status
        $staffList = $potentialAppraisees->map(function ($staff) use ($currentYear) {

            // Find the current year's appraisal for this staff member
            $appraisal = Appraisal::where('appraisee_id', $staff->id)
                ->where('year', $currentYear)
                ->first();

            return [
                'staff' => $staff,
                'appraisal' => $appraisal, // Null if appraisal doesn't exist
                'status' => $appraisal ? $appraisal->status : 'Not Started',
            ];
        });

        return view('pms.appraisal.index', compact('staffList', 'currentYear'));
    }
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Handles the initiation of a new Appraisal record.
     * This method bypasses showing a form and performs the store logic directly
     * based on the staff_id and year provided via the index link.
     */
    public function create(Request $request)
    {
        // 1. Get required parameters
        $staffId = $request->get('staff_id');
        $year = $request->get('year', now()->year);

        // 2. Basic Validation (Keep checks here before proceeding to modal)
        $staff = User::find($staffId);
        if (!$staff) {
            // This case should ideally not happen if the index view is built correctly
            return redirect()->route('appraisal.index')->with('error', 'Invalid staff member selected.');
        }

        // Check for duplicates
        $existingAppraisal = Appraisal::where('appraisee_id', $staffId)
            ->where('year', $year)
            ->first();
        if ($existingAppraisal) {
            return redirect()->route('appraisal.show', $existingAppraisal->id)
                ->with('info', 'Appraisal already exists and you have been redirected to the review page.');
        }

        // Find the accepted KPI to link
        $kpi = Kpi::where('assigned_to_staff_id', $staffId)
            ->where('year', $year)
            ->where('status', 'accepted')
            ->first();

        if (!$kpi) {
            return redirect()->route('appraisal.index')
                ->with('error', 'Cannot start appraisal: No accepted KPI found for the selected staff member in ' . $year . '.');
        }

        // 3. Fetch list of potential Appraiser 2 users (e.g., all Managers/HR/Admins excluding Appraiser 1)
        $potentialAppraisers = User::whereIn('access', ['Manager', 'HR', 'Admin'])
            ->where('id', '!=', Auth::id()) // Exclude Appraiser 1 (the current user)
            ->orderBy('name')->get();

        // 4. Return the view that contains the modal form
        return view('pms.appraisal.create', compact('staff', 'year', 'potentialAppraisers', 'kpi'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation for the Appraiser 2 selection
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'year' => 'required|integer',
            'appraiser_2_id' => 'nullable|exists:users,id|different:appraiser_1_id',
            'kpi_id' => 'required|exists:kpis,id',
        ]);

        $managerId = Auth::id(); // The logged-in user is Appraiser 1
        $staffId = $validated['staff_id'];
        $year = $validated['year'];

        // Re-check for duplicates (safety check)
        $existingAppraisal = Appraisal::where('appraisee_id', $staffId)
            ->where('year', $year)
            ->first();
        if ($existingAppraisal) {
            return redirect()->route('appraisal.show', $existingAppraisal->id)
                ->with('info', 'Appraisal already exists and you have been redirected to the review page.');
        }

        // 2. Create the new Appraisal record
        $newAppraisal = Appraisal::create([
            'appraisee_id' => $staffId,
            'appraiser_1_id' => $managerId,
            'appraiser_2_id' => $validated['appraiser_2_id'], // Use the selected Appraiser 2 ID
            'year' => $year,
            'status' => 'draft',
        ]);

        // 3. Link the KPI to the new Appraisal record
        $kpi = Kpi::find($validated['kpi_id']);
        $kpi->update(['appraisal_id' => $newAppraisal->id]);

        // 4. Redirect to the show view
        return redirect()->route('appraisal.show', $newAppraisal->id)
            ->with('success', 'New appraisal started successfully for ' . User::find($staffId)->name . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appraisal $appraisal)
    {
        // Load all necessary relationships, including the new competency scores
        $appraisal->load(['appraisee', 'appraiser1', 'appraiser2', 'goalScores', 'competencyScores']);

        // Load the KPI and its goals
        $kpi = $appraisal->kpi()->with('goals.trackings')->first();

        if (!$kpi) {
            return redirect()->back()->with('error', 'No KPI found for this appraisal.');
        }

        // Key scores for easy view lookup
        $goalScores = $appraisal->goalScores->keyBy('kpi_goal_id');
        // Key competency scores by attribute_key
        $competencyScores = $appraisal->competencyScores->keyBy('attribute_key');

        return view('pms.appraisal.show', compact('appraisal', 'kpi', 'goalScores', 'competencyScores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppraisalUpdateRequest $request, Appraisal $appraisal)
    {
        // 1. Setup Role and Action Checks
        $user = Auth::user();
        $isAppraisee = $user->id === $appraisal->appraisee_id;
        $isAppraiser1 = $user->id === $appraisal->appraiser_1_id;
        $isAppraiser2 = $user->id === $appraisal->appraiser_2_id;
        $action = $request->input('action', 'draft');

        // Prevent resubmission for an already submitted user
        if ($this->hasAlreadySubmitted($appraisal, $action)) {
            return redirect()->route('appraisal.show', $appraisal)
                ->with('error', 'Your part of the appraisal has already been submitted and is locked.');
        }

        try {
            DB::transaction(function () use ($request, $appraisal, $isAppraisee, $isAppraiser1, $isAppraiser2, $action) {
                // 2. Process all score-related sections
                $scores = $this->processAppraisalScores($request, $appraisal, $isAppraisee, $isAppraiser1, $isAppraiser2);

                // 3. Prepare static update data (comments, signatures, status)
                $updateData = $this->prepareStaticUpdateData($request, $appraisal, $scores, $isAppraisee, $isAppraiser1, $isAppraiser2, $action);

                // 4. Update the appraisal record
                $appraisal->update($updateData);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update appraisal: ' . $e->getMessage());
        }

        $message = ($action === 'draft')
            ? 'Appraisal saved as draft successfully.'
            : 'Your part of the appraisal has been submitted and locked successfully.';

        return redirect()->route('appraisal.show', $appraisal->id)->with('success', $message);
    }

    /**
     * Checks if the user trying to submit has already submitted.
     */
    protected function hasAlreadySubmitted(Appraisal $appraisal, string $action): bool
    {
        if ($action === 'appraisee_submit') {
            return $appraisal->appraisee_submitted;
        } elseif ($action === 'appraiser1_submit') {
            return $appraisal->appraiser1_submitted;
        } elseif ($action === 'appraiser2_submit') {
            return $appraisal->appraiser2_submitted;
        }
        return false;
    }

    /**
     * Processes Section 1 and Section 2 scores and calculates totals.
     */
    protected function processAppraisalScores(AppraisalUpdateRequest $request, Appraisal $appraisal, bool $isAppraisee, bool $isAppraiser1, bool $isAppraiser2): array
    {
        $appraiseeIsSubmitted = $appraisal->appraisee_submitted;
        $appraiser1IsSubmitted = $appraisal->appraiser1_submitted;
        $appraiser2IsSubmitted = $appraisal->appraiser2_submitted;

        $totalWeightedScore1 = $this->processSection1Scores($request, $appraisal, $isAppraisee, $isAppraiser1, $isAppraiser2, $appraiseeIsSubmitted, $appraiser1IsSubmitted, $appraiser2IsSubmitted);
        $section2Scores = $this->processSection2Scores($request, $appraisal, $isAppraisee, $isAppraiser1, $isAppraiser2, $appraiseeIsSubmitted, $appraiser1IsSubmitted, $appraiser2IsSubmitted);

        return [
            'section_1_score' => round($totalWeightedScore1, 2),
            'section_2a_score' => round($section2Scores['total_2a'], 2),
            'section_2b_score' => round($section2Scores['total_2b'], 2),
            'section_3_overall_score' => round($totalWeightedScore1 + $section2Scores['total_2a'] + $section2Scores['total_2b'], 2),
        ];
    }

    /**
     * Handles saving scores and recalculating total for Section 1 (KPI Goals).
     */
    protected function processSection1Scores(AppraisalUpdateRequest $request, Appraisal $appraisal, bool $isAppraisee, bool $isAppraiser1, bool $isAppraiser2, bool $appraiseeIsSubmitted, bool $appraiser1IsSubmitted, bool $appraiser2IsSubmitted): float
    {
        $totalWeightedScore1 = 0;

        if (!$request->has('goal_scores')) {
            $appraisal->loadMissing('goalScores');
            return $appraisal->goalScores->sum('weighted_score');
        }

        $appraisal->loadMissing('kpi.goals');
        $kpi = $appraisal->kpi;

        foreach ($request->input('goal_scores') as $kpiGoalId => $scores) {
            $goal = $kpi->goals->firstWhere('id', $kpiGoalId);
            if (!$goal) continue;

            $scoreRecord = AppraisalGoalScore::firstOrNew([
                'appraisal_id' => $appraisal->id,
                'kpi_goal_id' => $kpiGoalId,
            ]);

            // Logic to update scores only if the respective user has NOT submitted yet
            $changes = $this->updateScoreRecord($scoreRecord, $scores, $isAppraisee, $isAppraiser1, $isAppraiser2, $appraiseeIsSubmitted, $appraiser1IsSubmitted, $appraiser2IsSubmitted, 'appraiser_1_score', 'appraiser_2_score', 'staff_score');

            // Recalculate and update weighted score
            if ($scoreRecord->appraiser_1_score !== null && $scoreRecord->appraiser_2_score !== null) {
                $avgScore = ($scoreRecord->appraiser_1_score + $scoreRecord->appraiser_2_score) / 2;
                $scoreRecord->average_score = round($avgScore, 1);

                $weightedScore = $avgScore * ($goal->weightage / 100);
                $scoreRecord->weighted_score = round($weightedScore, 2);

                $changes = true;
            }

            if ($changes) {
                $scoreRecord->save();
            }
            $totalWeightedScore1 += $scoreRecord->weighted_score ?? 0;
        }

        return $totalWeightedScore1;
    }

    /**
     * Handles saving scores and recalculating totals for Section 2 (Competencies).
     */
    protected function processSection2Scores(AppraisalUpdateRequest $request, Appraisal $appraisal, bool $isAppraisee, bool $isAppraiser1, bool $isAppraiser2, bool $appraiseeIsSubmitted, bool $appraiser1IsSubmitted, bool $appraiser2IsSubmitted): array
    {
        $totalWeightedScore2a = 0;
        $totalWeightedScore2b = 0;
        $allAttributes = array_merge(AppraisalCompetencyScore::ATTRIBUTES_2A, AppraisalCompetencyScore::ATTRIBUTES_2B);

        foreach ($allAttributes as $key => $label) {
            $sectionType = array_key_exists($key, AppraisalCompetencyScore::ATTRIBUTES_2A) ? '2a' : '2b';

            $scoreRecord = AppraisalCompetencyScore::firstOrNew([
                'appraisal_id' => $appraisal->id,
                'attribute_key' => $key,
            ], ['section_type' => $sectionType]);

            $scores = $request->input("comp_scores.{$key}", []);

            // Logic to update scores only if the respective user has NOT submitted yet
            $changes = $this->updateScoreRecord($scoreRecord, $scores, $isAppraisee, $isAppraiser1, $isAppraiser2, $appraiseeIsSubmitted, $appraiser1IsSubmitted, $appraiser2IsSubmitted, 'appraiser_1_score', 'appraiser_2_score', 'staff_score');

            // Recalculate and update weighted score
            if ($scoreRecord->appraiser_1_score !== null && $scoreRecord->appraiser_2_score !== null) {
                $avgScore = ($scoreRecord->appraiser_1_score + $scoreRecord->appraiser_2_score) / 2;
                $scoreRecord->average_score = round($avgScore, 1);

                $weightedScore = $avgScore * 0.2;
                $scoreRecord->weighted_score = round($weightedScore, 2);

                $changes = true;
            }

            if ($changes) {
                $scoreRecord->save();
            }

            $currentWeighted = $scoreRecord->weighted_score ?? 0;
            if ($sectionType === '2a') {
                $totalWeightedScore2a += $currentWeighted;
            } else {
                $totalWeightedScore2b += $currentWeighted;
            }
        }

        return ['total_2a' => $totalWeightedScore2a, 'total_2b' => $totalWeightedScore2b];
    }

    /**
     * Generic method to update a score record based on user role and submission status.
     */
    protected function updateScoreRecord($scoreRecord, array $scores, bool $isAppraisee, bool $isAppraiser1, bool $isAppraiser2, bool $appraiseeIsSubmitted, bool $appraiser1IsSubmitted, bool $appraiser2IsSubmitted, string $a1Field, string $a2Field, string $staffField): bool
    {
        $changes = false;

        if ($isAppraisee && !$appraiseeIsSubmitted && array_key_exists($staffField, $scores)) {
            $scoreRecord->$staffField = $scores[$staffField];
            $changes = true;
        }
        if ($isAppraiser1 && !$appraiser1IsSubmitted && array_key_exists($a1Field, $scores)) {
            $scoreRecord->$a1Field = $scores[$a1Field];
            $changes = true;
        }
        if ($isAppraiser2 && !$appraiser2IsSubmitted && array_key_exists($a2Field, $scores)) {
            $scoreRecord->$a2Field = $scores[$a2Field];
            $changes = true;
        }

        return $changes;
    }

    /**
     * Collects non-score data, restricts comments/signatures based on role, and applies submission locks.
     */
    protected function prepareStaticUpdateData(AppraisalUpdateRequest $request, Appraisal $appraisal, array $scores, bool $isAppraisee, bool $isAppraiser1, bool $isAppraiser2, string $action): array
    {
        $appraiseeIsSubmitted = $appraisal->appraisee_submitted;
        $appraiser1IsSubmitted = $appraisal->appraiser1_submitted;
        $appraiser2IsSubmitted = $appraisal->appraiser2_submitted;

        // Collect general fields (editable by anyone who is currently not submitted)
        $updateData = $request->only([
            'kpi_goal_comments',
            'org_core_competency_comments',
            'job_family_competency_comments',
            'special_projects_comment',
            'major_achievements_comment',
            'promotion_potential_now',
            'promotion_potential_1_2_years',
            'promotion_potential_after_2_years',
            'promotion_now_comment',
            'promotion_1_2_years_comment',
            'promotion_after_2_years_comment',
            'personal_growth_comment',

            'appraisee_comments',
            'appraiser_1_comments',
            'appraiser_2_comments',
            'appraisee_signed',
            'appraiser1_signed',
            'appraiser2_signed',
        ]);

        // Add calculated scores
        $updateData = array_merge($updateData, $scores);

        // Default to preserving existing comments/signatures unless the user is meant to update them
        $updateData['appraisee_comments'] = $appraisal->appraisee_comments;
        $updateData['appraiser_1_comments'] = $appraisal->appraiser_1_comments;
        $updateData['appraiser_2_comments'] = $appraisal->appraiser_2_comments;

        $updateData['appraisee_signed'] = $appraisal->appraisee_signed;
        $updateData['appraiser1_signed'] = $appraisal->appraiser1_signed;
        $updateData['appraiser2_signed'] = $appraisal->appraiser2_signed;

        $updateData['appraisee_signed_at'] = $appraisal->appraisee_signed_at;
        $updateData['appraiser1_signed_at'] = $appraisal->appraiser1_signed_at;
        $updateData['appraiser2_signed_at'] = $appraisal->appraiser2_signed_at;

        $isSubmitted = false;

        // --- Role-Specific Comment, Signature, and Submission Overrides ---
        if ($isAppraisee && !$appraiseeIsSubmitted) {
            // Update comments/signatures from request
            $updateData['appraisee_comments'] = $request->input('appraisee_comments');
            $updateData['appraisee_signed'] = $request->has('appraisee_signed');

            // Set signed_at if checkbox is checked AND it's currently null
            if ($updateData['appraisee_signed'] && !$appraisal->appraisee_signed_at) {
                $updateData['appraisee_signed_at'] = Carbon::now();
            } elseif (!$updateData['appraisee_signed']) {
                $updateData['appraisee_signed_at'] = null; // Unchecked, remove timestamp
            }

            if ($action === 'appraisee_submit' && $updateData['appraisee_signed']) {
                $updateData['appraisee_submitted'] = true;
                $updateData['appraisee_signed_at'] = $updateData['appraisee_signed_at'] ?? Carbon::now(); // Ensure timestamp on submit
                $isSubmitted = true;
            }

            // Protect Appraiser's comments from being accidentally saved by Staff
            unset($updateData['appraiser_1_comments'], $updateData['appraiser_2_comments']);
        } elseif ($isAppraiser1 && !$appraiser1IsSubmitted) {
            // Update comments/signatures from request
            $updateData['appraiser_1_comments'] = $request->input('appraiser_1_comments');
            $updateData['appraiser1_signed'] = $request->has('appraiser1_signed');

            if ($updateData['appraiser1_signed'] && !$appraisal->appraiser1_signed_at) {
                $updateData['appraiser1_signed_at'] = Carbon::now();
            } elseif (!$updateData['appraiser1_signed']) {
                $updateData['appraiser1_signed_at'] = null;
            }

            if ($action === 'appraiser1_submit' && $updateData['appraiser1_signed']) {
                $updateData['appraiser1_submitted'] = true;
                $updateData['appraiser1_signed_at'] = $updateData['appraiser1_signed_at'] ?? Carbon::now();
                $isSubmitted = true;
            }

            unset($updateData['appraisee_comments'], $updateData['appraiser_2_comments']);
        } elseif ($isAppraiser2 && !$appraiser2IsSubmitted) {
            // Update comments/signatures from request
            $updateData['appraiser_2_comments'] = $request->input('appraiser_2_comments');
            $updateData['appraiser2_signed'] = $request->has('appraiser2_signed');

            if ($updateData['appraiser2_signed'] && !$appraisal->appraiser2_signed_at) {
                $updateData['appraiser2_signed_at'] = Carbon::now();
            } elseif (!$updateData['appraiser2_signed']) {
                $updateData['appraiser2_signed_at'] = null;
            }

            if ($action === 'appraiser2_submit' && $updateData['appraiser2_signed']) {
                $updateData['appraiser2_submitted'] = true;
                $updateData['appraiser2_signed_at'] = $updateData['appraiser2_signed_at'] ?? Carbon::now();
                $isSubmitted = true;
            }

            unset($updateData['appraisee_comments'], $updateData['appraiser_1_comments']);
        } else {
            // If submitted or unknown role, ignore all comment/signature inputs
            unset(
                $updateData['appraisee_comments'],
                $updateData['appraiser_1_comments'],
                $updateData['appraiser_2_comments'],
                $updateData['appraisee_signed'],
                $updateData['appraiser1_signed'],
                $updateData['appraiser2_signed'],
                $updateData['appraisee_signed_at'],
                $updateData['appraiser1_signed_at'],
                $updateData['appraiser2_signed_at']
            );
        }

        // If the overall status is 'draft' and a submission was made, change it to 'in_progress'
        if ($appraisal->status === 'draft' && $isSubmitted) {
            $updateData['status'] = 'in progress';
        }

        return $updateData;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
