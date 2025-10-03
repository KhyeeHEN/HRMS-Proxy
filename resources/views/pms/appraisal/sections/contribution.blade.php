<h4 class="mb-4 text-primary">Section 4: Staff Contribution</h4>

@php $readOnly = !$isAppraisee && !$isAppraiser1 && !$isAppraiser2;

    // Check if the current user has submitted their part
    $userSubmitted =
        ($isAppraisee && $appraisal->appraisee_submitted) ||
        ($isAppraiser1 && $appraisal->appraiser1_submitted) ||
        ($isAppraiser2 && $appraisal->appraiser2_submitted);

    // The field is read-only if the CURRENTLY LOGGED-IN user has submitted.
    $readOnly = $userSubmitted; 
@endphp

<div class="form-group">

    <label for="special_projects_comment">
        <h5 class='mt-4'>4(a) Special Projects & Assignments</h5>
    </label>
    <textarea class="form-control" id="special_projects_comment" name="special_projects_comment" rows="4"
        placeholder="..." {{ $readOnly ? 'readonly' : '' }}>{{ old('special_projects_comment', $appraisal->special_projects_comment) }}</textarea>
</div>

<div class="form-group">

    <label for="major_achievements_comment">
        <h5 class='mt-4'>4(b) Major Achievements</h5>
    </label>

    <textarea class="form-control" id="major_achievements_comment" name="major_achievements_comment" rows="4"
        placeholder="..." {{ $readOnly ? 'readonly' : '' }}>{{ old('major_achievements_comment', $appraisal->major_achievements_comment) }}</textarea>
</div>