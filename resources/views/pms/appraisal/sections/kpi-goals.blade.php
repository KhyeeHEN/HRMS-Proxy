<h4 class="mb-4 text-primary">Section 1: Key Goals and Objectives (KPIs)</h4>

@php
    $user = Auth::user();
    $isAppraisee = $user->id === $appraisal->appraisee_id;
    $isAppraiser1 = $user->id === $appraisal->appraiser_1_id;
    $isAppraiser2 = $user->id === $appraisal->appraiser_2_id;

    // ADDED: Submission status flags from the appraisal record
    $appraiseeIsSubmitted = $appraisal->appraisee_submitted;
    $appraiser1IsSubmitted = $appraisal->appraiser1_submitted;
    $appraiser2IsSubmitted = $appraisal->appraiser2_submitted;

    // Define edit access for Appraiser comments (Section 1 comments are Appraiser comments)
    $canEditAppraiserComment = ($isAppraiser1 && !$appraiser1IsSubmitted) || ($isAppraiser2 && !$appraiser2IsSubmitted);
@endphp

@foreach($kpi->goals as $i => $goal)
    @php
        // Look up the score record for this specific goal
        $scoreRecord = $goalScores->get($goal->id);

        // Calculate the current average (for display only)
        $avgScore = null;
        if ($scoreRecord && $scoreRecord->appraiser_1_score !== null && $scoreRecord->appraiser_2_score !== null) {
            $avgScore = ($scoreRecord->appraiser_1_score + $scoreRecord->appraiser_2_score) / 2;
        }
    @endphp

    <div class="card mb-4 border-left-primary" id="goal-card-{{ $goal->id }}">
        <div class="card-header bg-light">
            {{-- Added span with class for weightage retrieval --}}
            Goal {{ $i + 1 }}: {{ $goal->goal }} (Weightage: <span class="goal-weightage"
                data-goal-id="{{ $goal->id }}">{{ $goal->weightage }}</span>%)
        </div>
        <div class="card-body">
            <p><strong>Measurement:</strong> {{ $goal->measurement }}</p>

            <div class="row pt-3">
                <div class="col-md-3">
                    <label>Staff Score (1 - 100)</label>
                    <input type="number" step="0.1" min="1" max="100" class="form-control"
                        name="goal_scores[{{ $goal->id }}][staff_score]" value="{{ optional($scoreRecord)->staff_score }}"
                        {{-- FIX: Only editable if Appraisee AND NOT submitted --}}{{ $isAppraisee && !$appraiseeIsSubmitted ? '' : 'readonly' }}>
                </div>

                <div class="col-md-3">
                    <label>Appraiser 1 Score (1 - 100)</label>
                    <input type="number" step="0.1" min="1" max="100" class="form-control appraiser-score"
                        name="goal_scores[{{ $goal->id }}][appraiser_1_score]"
                        value="{{ optional($scoreRecord)->appraiser_1_score }}" data-goal-id="{{ $goal->id }}"
                        data-appraiser="1" {{-- FIX: Only editable if Appraiser 1 AND NOT submitted --}}{{ $isAppraiser1 && !$appraiser1IsSubmitted ? '' : 'readonly' }}>
                </div>

                <div class="col-md-3">
                    <label>Appraiser 2 Score (1 - 100)</label>
                    <input type="number" step="0.1" min="1" max="100" class="form-control appraiser-score"
                        name="goal_scores[{{ $goal->id }}][appraiser_2_score]"
                        value="{{ optional($scoreRecord)->appraiser_2_score }}" data-goal-id="{{ $goal->id }}"
                        data-appraiser="2" {{-- FIX: Only editable if Appraiser 2 AND NOT submitted --}}    {{ $isAppraiser2 && !$appraiser2IsSubmitted ? '' : 'readonly' }}>
                </div>

                <div class="col-md-3">
                    <label class="text-white d-block">.</label>
                    <p class="mb-0"><strong>Avg Score:</strong>
                        <span class="text-success font-weight-bold average-score-display" data-goal-id="{{ $goal->id }}">
                            {{ $avgScore ? number_format($avgScore, 1) : 'N/A' }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Weighted Score:</strong>
                        <span class="text-info font-weight-bold weighted-score-display" data-goal-id="{{ $goal->id }}"
                            data-current-score="{{ optional($scoreRecord)->weighted_score ?? 0 }}">
                            {{ optional($scoreRecord)->weighted_score ? number_format($scoreRecord->weighted_score, 2) : 'N/A' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endforeach

<hr>
<div class="form-group mt-4">
    <label for="kpi_goal_comments" class="font-weight-bold">Appraiser Comments on Section 1 (Key Goals &
        Objectives)</label>
    <textarea class="form-control" id="kpi_goal_comments" name="kpi_goal_comments" rows="4" {{-- FIX: Readonly unless
        Appraiser 1 OR Appraiser 2 is logged in and not submitted --}} {{ !$canEditAppraiserComment ? 'readonly' : '' }}>{{ old('kpi_goal_comments', $appraisal->kpi_goal_comments) }}</textarea>
</div>

<hr>

<div class="form-group row mt-4">
    <label for="section_1_score" class="col-sm-6 col-form-label font-weight-bold text-danger">
        Final Section 1 Total Score (Weighted Sum)
    </label>
    <div class="col-sm-3">
        <input type="text" readonly class="form-control-plaintext font-weight-bold text-danger"
            id="section-1-total-display" value="{{ number_format($appraisal->section_1_score, 2) ?? '0.00' }}">
        <input type="hidden" name="section_1_score" id="section-1-total-hidden"
            value="{{ $appraisal->section_1_score }}">
    </div>
    <div class="col-sm-3">
        <small class="form-text text-muted">This is calculated automatically by the system.</small>
    </div>
</div>