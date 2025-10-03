<h4 class="mb-4 text-primary">Section 6: Comments & Acknowledgement</h4>

@php
    // Define submitted status flags
    $appraiseeIsSubmitted = $appraisal->appraisee_submitted;
    $appraiser1IsSubmitted = $appraisal->appraiser1_submitted;
    $appraiser2IsSubmitted = $appraisal->appraiser2_submitted;
@endphp

{{-- Appraisee's Comments --}}
<div class="form-group mt-3">
    <label for="appraisee_comments" class="font-weight-bold">
        <h5 class="mt-4">6(a) Appraisee's Comments</h5>
    </label>
     {{-- Field is READ-ONLY if Appraisee submitted, OR if the current user is NOT the Appraisee --}}
    @php $staffCommentReadOnly = $appraiseeIsSubmitted || !$isAppraisee; @endphp 
    
    <textarea class="form-control" 
              id="appraisee_comments" 
              name="appraisee_comments" 
              rows="4" 
              placeholder="Enter your final comments on the overall assessment."
              {{ $staffCommentReadOnly ? 'readonly' : '' }}
    >{{ old('appraisee_comments', $appraisal->appraisee_comments) }}</textarea>
    {{-- Acknowledgement Checkbox for Appraisee --}}
    @if ($isAppraisee)
        <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input signature-check" id="appraisee_signed" name="appraisee_signed" value="1" 
                   {{ $appraisal->appraisee_signed ? 'checked' : '' }} 
                   {{ $appraiseeIsSubmitted ? 'disabled' : '' }}>
            <label class="form-check-label" for="appraisee_signed">I acknowledge and sign this appraisal.</label>
        </div>
    @elseif ($appraiseeIsSubmitted)
        <p class="text-success mt-2">Staff Signed & Submitted</p>
    @elseif ($appraisal->appraisee_signed)
        <p class="text-info mt-2">Staff Signed (Pending Submission)</p>
    @endif
</div>

{{-- Appraiser 1's Comments --}}
<div class="form-group mt-3">
    <label for="appraiser_1_comments" class="font-weight-bold">
        <h5 class="mt-4">6(b) Appraiser 1's Comments ({{ optional($appraisal->appraiser1)->name }})</h5>
    </label>
    {{-- Field is READ-ONLY if Appraiser 1 submitted, OR if the current user is NOT Appraiser 1 --}}
    @php $appraiser1CommentReadOnly = $appraiser1IsSubmitted || !$isAppraiser1; @endphp
    
    <textarea class="form-control" 
              id="appraiser_1_comments" 
              name="appraiser_1_comments" 
              rows="4" 
              placeholder="Enter Appraiser 1's comments."
              {{ $appraiser1CommentReadOnly ? 'readonly' : '' }}
    >{{ old('appraiser_1_comments', $appraisal->appraiser_1_comments) }}</textarea>

    @if ($isAppraiser1)
        <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input signature-check" id="appraiser1_signed" name="appraiser1_signed" value="1" 
                   {{ $appraisal->appraiser1_signed ? 'checked' : '' }} 
                   {{ $appraiser1IsSubmitted ? 'disabled' : '' }}>
            <label class="form-check-label" for="appraiser1_signed">I acknowledge and sign this appraisal.</label>
        </div>
    @elseif ($appraiser1IsSubmitted)
        <p class="text-success mt-2">**Appraiser 1 Signed & Submitted**</p>
    @elseif ($appraisal->appraiser1_signed)
        <p class="text-info mt-2">**Appraiser 1 Signed (Pending Submission)**</p>
    @endif
</div>

{{-- Appraiser 2's Comments --}}

<div class="form-group mt-3">
    <label for="appraiser_2_comments" class="font-weight-bold">
        <h5 class="mt-4">6(c) Appraiser 2's Comments ({{ optional($appraisal->appraiser2)->name }})</h5>
    </label>
    {{-- Field is READ-ONLY if Appraiser 2 submitted, OR if the current user is NOT Appraiser 2 --}}
    @php $appraiser2CommentReadOnly = $appraiser2IsSubmitted || !$isAppraiser2; @endphp
    
    <textarea class="form-control" 
              id="appraiser_2_comments" 
              name="appraiser_2_comments" 
              rows="4" 
              placeholder="Enter Appraiser 2's comments."
              {{ $appraiser2CommentReadOnly ? 'readonly' : '' }}
    >{{ old('appraiser_2_comments', $appraisal->appraiser_2_comments) }}</textarea>

    @if ($isAppraiser2)
        <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input signature-check" id="appraiser2_signed" name="appraiser2_signed" value="1" 
                   {{ $appraisal->appraiser2_signed ? 'checked' : '' }} 
                   {{ $appraiser2IsSubmitted ? 'disabled' : '' }}>
            <label class="form-check-label" for="appraiser2_signed">I acknowledge and sign this appraisal.</label>
        </div>
    @elseif ($appraiser2IsSubmitted)
        <p class="text-success mt-2">Appraiser 2 Signed & Submitted</p>
    @elseif ($appraisal->appraiser2_signed)
        <p class="text-info mt-2">Appraiser 2 Signed (Pending Submission)</p>
    @endif
</div>
