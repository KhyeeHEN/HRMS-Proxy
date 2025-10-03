<h4 class="mb-4 text-primary">Section 2: Competency Evaluation</h4>

@php
    use App\Models\AppraisalCompetencyScore;
    $user = Auth::user();
    $isAppraisee = $user->id === $appraisal->appraisee_id;
    $isAppraiser1 = $user->id === $appraisal->appraiser_1_id;
    $isAppraiser2 = $user->id === $appraisal->appraiser_2_id;

    // ADDED: Submission status flags
    $appraiseeIsSubmitted = $appraisal->appraisee_submitted;
    $appraiser1IsSubmitted = $appraisal->appraiser1_submitted;
    $appraiser2IsSubmitted = $appraisal->appraiser2_submitted;

    // Define edit access for Appraiser comments
    $canEditAppraiserComment = ($isAppraiser1 && !$appraiser1IsSubmitted) || ($isAppraiser2 && !$appraiser2IsSubmitted);

    // Previous $readOnly variable is removed/ignored here to apply specific role locks below.

    // Note: The total scores here are used for initial display. JS will update them.
    $totalScore2a = 0;
    $totalScore2b = 0;
    $appraisal->competencyScores->each(function ($score) use (&$totalScore2a, &$totalScore2b) {
        if ($score->section_type === '2a') {
            $totalScore2a += $score->weighted_score ?? 0;
        } else {
            $totalScore2b += $score->weighted_score ?? 0;
        }
    });
@endphp

{{-- SECTION 2(a) --}}
<h5 class="mt-4">2(a) Organizational Core Competencies</h5>
<table class="table table-bordered" id="table-2a"> {{-- Added ID for table reference --}}
    <thead class="thead-light">
        <tr>
            <th style="width: 30%;">Competency Attribute</th>
            <th style="width: 14%;">Staff Score (0-10)</th>
            <th style="width: 14%;">Appraiser 1 (0-10)</th>
            <th style="width: 14%;">Appraiser 2 (0-10)</th>
            <th style="width: 14%;">Average (A1/A2)</th>
            <th style="width: 14%;">Weighted (x0.2)</th>
        </tr>
    </thead>
    <tbody>
        @foreach(AppraisalCompetencyScore::ATTRIBUTES_2A as $key => $label)
            @php
                $scoreRecord = $competencyScores->get($key);
                $a1Score = optional($scoreRecord)->appraiser_1_score;
                $a2Score = optional($scoreRecord)->appraiser_2_score;
                $weightedScore = optional($scoreRecord)->weighted_score;
            @endphp
            <tr>
                <td>{{ $label }}</td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control"
                        name="comp_scores[{{ $key }}][staff_score]" value="{{ optional($scoreRecord)->staff_score }}" {{--
                        FIX: Only editable if Appraisee AND NOT submitted --}} {{ $isAppraisee && !$appraiseeIsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control comp-score-input"
                        name="comp_scores[{{ $key }}][appraiser_1_score]" value="{{ $a1Score }}"
                        data-attribute-key="{{ $key }}" data-section="2a" data-appraiser="1" {{-- FIX: Only editable if
                        Appraiser 1 AND NOT submitted --}} {{ $isAppraiser1 && !$appraiser1IsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control comp-score-input"
                        name="comp_scores[{{ $key }}][appraiser_2_score]" value="{{ $a2Score }}"
                        data-attribute-key="{{ $key }}" data-section="2a" data-appraiser="2" {{-- FIX: Only editable if
                        Appraiser 2 AND NOT submitted --}} {{ $isAppraiser2 && !$appraiser2IsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <span class="font-weight-bold text-success comp-average-display" data-attribute-key="{{ $key }}">
                        {{ optional($scoreRecord)->average_score ? number_format($scoreRecord->average_score, 1) : 'N/A' }}
                    </span>
                </td>
                <td>
                    <span class="font-weight-bold text-info comp-weighted-display" data-attribute-key="{{ $key }}"
                        data-current-score="{{ $weightedScore ?? 0 }}">
                        {{ $weightedScore ? number_format($weightedScore, 2) : 'N/A' }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="form-group row mt-4 border-top pt-3">
    <label class="col-sm-8 col-form-label font-weight-bold text-danger">
        Final Section 2(a) Total Score (Weighted Sum)
    </label>
    <div class="col-sm-4">
        <input type="text" readonly class="form-control-plaintext font-weight-bold text-danger"
            id="section-2a-total-display" value="{{ number_format($totalScore2a, 2) }}">
        <input type="hidden" name="section_2a_score" id="section-2a-total-hidden" value="{{ $totalScore2a }}">
    </div>
</div>

<div class="form-group mt-3">
    <label for="org_core_competency_comments" class="font-weight-bold">
        Appraiser Comments on Organizational Core Competencies (Section 2a)
    </label>
    <textarea class="form-control" id="org_core_competency_comments" name="org_core_competency_comments" rows="3"
        placeholder="Enter summary comments for organizational core competencies." {{-- FIX: Readonly unless Appraiser 1
        OR Appraiser 2 is logged in and not submitted --}} {{ !$canEditAppraiserComment ? 'readonly' : '' }}>{{ old('org_core_competency_comments', $appraisal->org_core_competency_comments) }}</textarea>
</div>


{{-- SECTION 2(b) --}}
<h5 class="mt-5">2(b) Job Family Competencies</h5>
<table class="table table-bordered" id="table-2b">
    <thead class="thead-light">
        <tr>
            <th style="width: 30%;">Competency Attribute</th>
            <th style="width: 14%;">Staff Score (0-10)</th>
            <th style="width: 14%;">Appraiser 1 (0-10)</th>
            <th style="width: 14%;">Appraiser 2 (0-10)</th>
            <th style="width: 14%;">Average (A1/A2)</th>
            <th style="width: 14%;">Weighted (x0.2)</th>
        </tr>
    </thead>
    <tbody>
        @foreach(AppraisalCompetencyScore::ATTRIBUTES_2B as $key => $label)
            @php
                $scoreRecord = $competencyScores->get($key);
                $a1Score = optional($scoreRecord)->appraiser_1_score;
                $a2Score = optional($scoreRecord)->appraiser_2_score;
                $weightedScore = optional($scoreRecord)->weighted_score;
            @endphp
            <tr>
                <td>{{ $label }}</td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control"
                        name="comp_scores[{{ $key }}][staff_score]" value="{{ optional($scoreRecord)->staff_score }}" {{--
                        FIX: Only editable if Appraisee AND NOT submitted --}} {{ $isAppraisee && !$appraiseeIsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control comp-score-input"
                        name="comp_scores[{{ $key }}][appraiser_1_score]" value="{{ $a1Score }}"
                        data-attribute-key="{{ $key }}" data-section="2b" data-appraiser="1" {{-- FIX: Only editable if
                        Appraiser 1 AND NOT submitted --}} {{ $isAppraiser1 && !$appraiser1IsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <input type="number" step="0.1" min="0" max="10" class="form-control comp-score-input"
                        name="comp_scores[{{ $key }}][appraiser_2_score]" value="{{ $a2Score }}"
                        data-attribute-key="{{ $key }}" data-section="2b" data-appraiser="2" {{-- FIX: Only editable if
                        Appraiser 2 AND NOT submitted --}} {{ $isAppraiser2 && !$appraiser2IsSubmitted ? '' : 'readonly' }}>
                </td>
                <td>
                    <span class="font-weight-bold text-success comp-average-display" data-attribute-key="{{ $key }}">
                        {{ optional($scoreRecord)->average_score ? number_format(optional($scoreRecord)->average_score, 1) : 'N/A' }}
                    </span>
                </td>
                <td>
                    <span class="font-weight-bold text-info comp-weighted-display" data-attribute-key="{{ $key }}"
                        data-current-score="{{ $weightedScore ?? 0 }}">
                        {{ $weightedScore ? number_format($weightedScore, 2) : 'N/A' }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="form-group row mt-4 border-top pt-3">
    <label class="col-sm-8 col-form-label font-weight-bold text-danger">
        Final Section 2(b) Total Score (Weighted Sum)
    </label>
    <div class="col-sm-4">
        <input type="text" readonly class="form-control-plaintext font-weight-bold text-danger"
            id="section-2b-total-display" value="{{ number_format($totalScore2b, 2) }}">
        <input type="hidden" name="section_2b_score" id="section-2b-total-hidden" value="{{ $totalScore2b }}">
    </div>
</div>

<div class="form-group mt-3">
    <label for="job_family_competency_comments" class="font-weight-bold">
        Appraiser Comments on Job Family Competencies (Section 2b)
    </label>
    <textarea class="form-control" id="job_family_competency_comments" name="job_family_competency_comments" rows="3"
        placeholder="Enter summary comments for job family competencies." {{-- FIX: Readonly unless Appraiser 1 OR
        Appraiser 2 is logged in and not submitted --}} {{ !$canEditAppraiserComment ? 'readonly' : '' }}>{{ old('job_family_competency_comments', $appraisal->job_family_competency_comments) }}</textarea>
</div>

<hr class="my-5">

{{-- SECTION 3: OVERALL SCORE --}}
<h4 class="mt-4">Section 3: Overall Performance Assessment Score</h4>
<div class="form-group row">
    <label class="col-sm-8 col-form-label font-weight-bold text-success">
        Section 3: Overall Performance Score (Calculated)
    </label>
    <div class="col-sm-4">
        <input type="text" readonly class="form-control-plaintext font-weight-bold text-success"
            id="section-3-overall-score-display"
            value="{{ number_format($appraisal->section_3_overall_score, 2) ?? 'N/A' }}">
        <input type="hidden" name="section_3_overall_score" id="section-3-overall-score-hidden"
            value="{{ $appraisal->section_3_overall_score }}">
    </div>
</div>