@extends('layout')

@section('title', 'Performance Appraisal: ' . $appraisal->appraisee->name)

@php
    $user = Auth::user();
    $isAppraisee = $user->id === $appraisal->appraisee_id;
    $isAppraiser1 = $user->id === $appraisal->appraiser_1_id;
    $isAppraiser2 = $user->id === $appraisal->appraiser_2_id;
@endphp

@php
    $canSubmit = false;
    $submitRole = '';
    $submitStatusColumn = '';
    $signatureColumn = '';

    if ($isAppraisee && !$appraisal->appraisee_submitted) {
        $canSubmit = true;
        $submitRole = 'appraisee';
        $submitStatusColumn = 'appraisee_submitted';
        $signatureColumn = 'appraisee_signed';
    } elseif ($isAppraiser1 && !$appraisal->appraiser1_submitted) {
        $canSubmit = true;
        $submitRole = 'appraiser1';
        $submitStatusColumn = 'appraiser1_submitted';
        $signatureColumn = 'appraiser1_signed';
    } elseif ($isAppraiser2 && !$appraisal->appraiser2_submitted) {
        $canSubmit = true;
        $submitRole = 'appraiser2';
        $submitStatusColumn = 'appraiser2_submitted';
        $signatureColumn = 'appraiser2_signed';
    }
@endphp

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Performance Assessment for {{ $appraisal->appraisee->name }}
            ({{ $appraisal->year }})</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @include('partials.error') {{-- To display validation errors --}}

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Assessment Sections</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('appraisal.update', $appraisal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- 1. Tab Navigation (The actual tabs) --}}
                    <ul class="nav nav-tabs" id="appraisalTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="kpi-tab" data-toggle="tab" href="#kpi-content" role="tab"
                                aria-controls="kpi-content" aria-selected="true">
                                1. Key Goals & Objectives
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="competency-tab" data-toggle="tab" href="#competency-content" role="tab"
                                aria-controls="competency-content" aria-selected="false">
                                2. Competency Evaluation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contribution-tab" data-toggle="tab" href="#contribution-content"
                                role="tab" aria-controls="contribution-content" aria-selected="false">
                                3. Staff Contribution
                            </a>
                        </li>
                        @if (!$isAppraisee)
                            <li class="nav-item">
                                <a class="nav-link" id="growth-tab" data-toggle="tab" href="#growth-content" role="tab"
                                    aria-controls="growth-content" aria-selected="false">
                                    4. Career & Growth
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments-content" role="tab"
                                aria-controls="comments-content" aria-selected="false">
                                5. Final Comments
                            </a>
                        </li>
                    </ul>

                    {{-- 2. Tab Content (The forms for each section) --}}
                    <div class="tab-content" id="appraisalTabsContent">

                        {{-- 1. KPI Goals Tab (Active by Default) --}}
                        <div class="tab-pane fade show active p-4" id="kpi-content" role="tabpanel"
                            aria-labelledby="kpi-tab">
                            @include('pms.appraisal.sections.kpi-goals')
                        </div>

                        {{-- 2. Competency Tab --}}
                        <div class="tab-pane fade p-4" id="competency-content" role="tabpanel"
                            aria-labelledby="competency-tab">
                            @include('pms.appraisal.sections.competency')
                        </div>

                        {{-- 3. Staff Contribution Tab --}}
                        <div class="tab-pane fade p-4" id="contribution-content" role="tabpanel"
                            aria-labelledby="contribution-tab">
                            @include('pms.appraisal.sections.contribution')
                        </div>

                        @if (!$isAppraisee)
                            {{-- 4. Career & Growth Tab --}}
                            <div class="tab-pane fade p-4" id="growth-content" role="tabpanel" aria-labelledby="growth-tab">
                                @include('pms.appraisal.sections.career-growth')
                            </div>
                        @endif

                        {{-- 5. Final Comments Tab --}}
                        <div class="tab-pane fade p-4" id="comments-content" role="tabpanel" aria-labelledby="comments-tab">
                            @include('pms.appraisal.sections.final-comments')
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        {{-- Save as Draft: Sets action=draft --}}
                        <button type="submit" class="btn btn-secondary" name="action" value="draft" action="draft">
                            <i class="fas fa-save"></i> Save as Draft
                        </button>
                        {{-- Submit Button (Finalizes and locks the user's data) --}}
                        @if ($canSubmit)
                            <button type="submit" name="action" value="{{ $submitRole }}_submit"
                                class="btn btn-success submit-final-btn" id="submit-{{ $submitRole }}-btn"
                                data-signature-id="{{ $signatureColumn }}" {{ !$appraisal->$signatureColumn ? 'disabled' : '' }}>
                                Submit Final
                            </button>
                        @else
                            <button type="button" class="btn btn-success" disabled>
                                {{ $isAppraisee ? 'Staff Submitted' : ($isAppraiser1 ? 'Appraiser 1 Submitted' : ($isAppraiser2 ? 'Appraiser 2 Submitted' : 'View Only')) }}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ------------------------------------------------------------------
            // | 1. ELEMENT SELECTION                                           |
            // ------------------------------------------------------------------

            // --- Section 1 (KPI Goals) Elements ---
            const appraiserGoalScoreInputs = document.querySelectorAll('.appraiser-score');
            const hiddenTotalScore1Input = document.getElementById('section-1-total-hidden');
            const displayTotalScore1Input = document.getElementById('section-1-total-display');

            // --- Section 2 (Competency) Elements ---
            const appraiserCompScoreInputs = document.querySelectorAll('.comp-score-input');
            const hiddenTotalScore2aInput = document.getElementById('section-2a-total-hidden');
            const displayTotalScore2aInput = document.getElementById('section-2a-total-display');
            const hiddenTotalScore2bInput = document.getElementById('section-2b-total-hidden');
            const displayTotalScore2bInput = document.getElementById('section-2b-total-display');

            // --- Section 3 (Overall Total) Elements (Add these IDs to your view if they don't exist) ---
            const displayTotalScore3Input = document.getElementById('section-3-overall-score-display');
            const hiddenTotalScore3Input = document.getElementById('section-3-overall-score-hidden');


            // -----------------------------------------------------------------
            // | 2. SECTION 1 (KPI Goals) FUNCTIONS (Scale: 1-100, Weightage: Variable) |
            // -----------------------------------------------------------------

            /** Calculates the average and weighted score for a KPI Goal. */
            function calculateWeightedScore(a1, a2, weightage) {
                if (!isNaN(a1) && a1 !== null && !isNaN(a2) && a2 !== null) {
                    const avg = (a1 + a2) / 2;
                    const weighted = avg * (weightage / 100);
                    return { avg: avg.toFixed(1), weighted: weighted.toFixed(2), rawWeighted: weighted };
                }
                return { avg: 'N/A', weighted: 'N/A', rawWeighted: 0 };
            }

            /** Updates the individual score display and calls the Section 1 total update. */
            function updateGoalScore(goalId) {
                const goalCard = document.getElementById(`goal-card-${goalId}`);

                // 1. Get current scores and weightage
                const a1Input = goalCard.querySelector(`input[data-goal-id="${goalId}"][data-appraiser="1"]`);
                const a2Input = goalCard.querySelector(`input[data-goal-id="${goalId}"][data-appraiser="2"]`);
                const weightageElement = goalCard.querySelector('.goal-weightage');
                const avgDisplay = goalCard.querySelector('.average-score-display');
                const weightedDisplay = goalCard.querySelector('.weighted-score-display');

                const a1Score = parseFloat(a1Input.value);
                const a2Score = parseFloat(a2Input.value);
                const weightage = parseFloat(weightageElement.textContent);

                // Store the old weighted score before recalculating
                const oldWeightedScore = parseFloat(weightedDisplay.dataset.currentScore || 0);

                // 2. Perform calculation
                const result = calculateWeightedScore(
                    isNaN(a1Score) ? null : a1Score,
                    isNaN(a2Score) ? null : a2Score,
                    weightage
                );

                // 3. Update displays and data attribute
                avgDisplay.textContent = result.avg;
                weightedDisplay.textContent = result.weighted;
                weightedDisplay.dataset.currentScore = result.rawWeighted;

                // 4. Update Section 1 Total Score (using '1' as the sectionType identifier)
                updateSectionTotal('1', oldWeightedScore, result.rawWeighted);
            }

            // ------------------------------------------------------------------
            // | 3. SECTION 2 (Competency) FUNCTIONS (Scale: 0-10, Weightage: 0.2) |
            // ------------------------------------------------------------------

            /** Calculates the average and weighted score for a Competency. */
            function calculateCompetencyWeightedScore(a1, a2) {
                if (!isNaN(a1) && a1 !== null && !isNaN(a2) && a2 !== null) {
                    const avg = (a1 + a2) / 2;
                    // Weighted Score = Average Score * 0.2
                    const weighted = avg * 0.2;
                    return {
                        avg: avg.toFixed(1),
                        weighted: weighted.toFixed(2),
                        rawWeighted: weighted
                    };
                }
                return { avg: 'N/A', weighted: 'N/A', rawWeighted: 0 };
            }

            /** Updates the individual competency score display and calls the Section 2 total update. */
            function updateCompetencyScore(attributeKey, sectionType) {
                const rowSelector = `[data-attribute-key="${attributeKey}"]`;
                const parentTable = document.getElementById(`table-${sectionType}`);

                // 1. Get current scores
                const a1Input = parentTable.querySelector(`input.comp-score-input${rowSelector}[data-appraiser="1"]`);
                const a2Input = parentTable.querySelector(`input.comp-score-input${rowSelector}[data-appraiser="2"]`);
                const avgDisplay = parentTable.querySelector(`span.comp-average-display${rowSelector}`);
                const weightedDisplay = parentTable.querySelector(`span.comp-weighted-display${rowSelector}`);

                const a1Score = parseFloat(a1Input.value);
                const a2Score = parseFloat(a2Input.value);

                // Store the old weighted score before recalculating
                const oldWeightedScore = parseFloat(weightedDisplay.dataset.currentScore || 0);

                // 2. Perform calculation
                const result = calculateCompetencyWeightedScore(
                    isNaN(a1Score) ? null : a1Score,
                    isNaN(a2Score) ? null : a2Score
                );

                // 3. Update displays and data attribute
                avgDisplay.textContent = result.avg;
                weightedDisplay.textContent = result.weighted;
                weightedDisplay.dataset.currentScore = result.rawWeighted;

                // 4. Update Section 2 Total Score
                updateSectionTotal(sectionType, oldWeightedScore, result.rawWeighted);
            }

            // ------------------------------------------------------------------
            // | 4. TOTAL SCORE FUNCTIONS (S1, S2a, S2b, S3)                    |
            // ------------------------------------------------------------------

            /** Updates the total score for a given section (1, 2a, or 2b). */
            function updateSectionTotal(sectionType, oldWeightedScore, newWeightedScore) {
                let hiddenInput, displayInput;

                if (sectionType === '1') {
                    hiddenInput = hiddenTotalScore1Input;
                    displayInput = displayTotalScore1Input;
                } else if (sectionType === '2a') {
                    hiddenInput = hiddenTotalScore2aInput;
                    displayInput = displayTotalScore2aInput;
                } else if (sectionType === '2b') {
                    hiddenInput = hiddenTotalScore2bInput;
                    displayInput = displayTotalScore2bInput;
                } else {
                    return;
                }

                let currentTotal = parseFloat(hiddenInput.value) || 0;

                // Subtract the old score, then add the new calculated score
                currentTotal = currentTotal - oldWeightedScore + newWeightedScore;

                // Update the display and the hidden input for submission
                displayInput.value = currentTotal.toFixed(2);
                hiddenInput.value = currentTotal.toFixed(2);

                // Always update the overall total (Section 3) after any sub-section update
                updateSection3Total();
            }

            /** Calculates and updates the overall Section 3 score (S1 + S2a + S2b). */
            function updateSection3Total() {
                if (displayTotalScore3Input && hiddenTotalScore3Input) {
                    const total1 = parseFloat(hiddenTotalScore1Input.value) || 0;
                    const total2a = parseFloat(hiddenTotalScore2aInput.value) || 0;
                    const total2b = parseFloat(hiddenTotalScore2bInput.value) || 0;

                    const overallTotal = total1 + total2a + total2b;

                    displayTotalScore3Input.value = overallTotal.toFixed(2);
                    hiddenTotalScore3Input.value = overallTotal.toFixed(2);
                }
            }

            /** Recalculates all section totals on page load based on current data. */
            function calculateInitialTotals() {
                // Section 1 (KPI)
                let initialTotal1 = 0;
                document.querySelectorAll('.weighted-score-display').forEach(display => {
                    initialTotal1 += parseFloat(display.dataset.currentScore || 0);
                });
                displayTotalScore1Input.value = initialTotal1.toFixed(2);
                hiddenTotalScore1Input.value = initialTotal1.toFixed(2);

                // Section 2a
                let initialTotal2a = 0;
                document.querySelectorAll('#table-2a .comp-weighted-display').forEach(display => {
                    initialTotal2a += parseFloat(display.dataset.currentScore || 0);
                });
                displayTotalScore2aInput.value = initialTotal2a.toFixed(2);
                hiddenTotalScore2aInput.value = initialTotal2a.toFixed(2);

                // Section 2b
                let initialTotal2b = 0;
                document.querySelectorAll('#table-2b .comp-weighted-display').forEach(display => {
                    initialTotal2b += parseFloat(display.dataset.currentScore || 0);
                });
                displayTotalScore2bInput.value = initialTotal2b.toFixed(2);
                hiddenTotalScore2bInput.value = initialTotal2b.toFixed(2);

                // Overall Section 3
                updateSection3Total();
            }


            // ------------------------------------------------------------------
            // | 5. EVENT LISTENERS                                             |
            // ------------------------------------------------------------------

            // --- Section 1 (KPI) Listeners ---
            appraiserGoalScoreInputs.forEach(input => {
                input.addEventListener('input', function () {
                    // Basic client-side validation check (1-100)
                    if (parseFloat(this.value) < 1 || parseFloat(this.value) > 100) {
                        return;
                    }
                    const goalId = this.dataset.goalId;
                    updateGoalScore(goalId);
                });
            });

            // --- Section 2 (Competency) Listeners ---
            appraiserCompScoreInputs.forEach(input => {
                input.addEventListener('input', function () {
                    // Basic client-side validation check (0-10)
                    if (parseFloat(this.value) < 0 || parseFloat(this.value) > 10) {
                        return;
                    }
                    const attributeKey = this.dataset.attributeKey;
                    const sectionType = this.dataset.section;
                    updateCompetencyScore(attributeKey, sectionType);
                });
            });

            // --- 6. INITIALIZATION ---
            calculateInitialTotals();
        });

        const signatureCheckboxes = document.querySelectorAll('.signature-check');

        // Function to update the disabled state of the user's submit button
        function updateSubmitButtonState() {
            const submitButton = document.querySelector('.submit-final-btn');
            if (submitButton) {
                const signatureId = submitButton.getAttribute('data-signature-id');
                const signatureCheckbox = document.getElementById(signatureId);

                // Disable button if the signature is not checked
                submitButton.disabled = !signatureCheckbox || !signatureCheckbox.checked;
            }
        }

        // Add listeners to signature checkboxes
        signatureCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSubmitButtonState);
        });

        // Initial check on page load
        updateSubmitButtonState();
    </script>
@endpush