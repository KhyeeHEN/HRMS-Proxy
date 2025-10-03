<h4 class="mt-4">Section 5: Career Development & Personal Growth</h4>

{{-- Remember: This entire section is hidden from the Appraisee in show.blade.php --}}
{{-- Editable ONLY by Appraiser 1 or Appraiser 2 --}}
@php 
    // Assuming $isAppraiser1 and $isAppraiser2 are available from the parent view
    $readOnly = !$isAppraiser1 && !$isAppraiser2; 
    $options = ['High', 'Low', 'Not Ready'];
@endphp 

<h5 class="mt-4">5(a) Career Development - Potential for Promotion</h5>
<p class="text-muted">Assess the appraisee's potential for promotion for each time period.</p>


<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th style="width: 25%;">Time Period</th>
                <th style="width: 20%;">Assessment</th>
                <th style="width: 55%;">Appraiser Comment</th>
            </tr>
        </thead>
        <tbody>
            {{-- TIME PERIOD: NOW --}}
            <tr>
                <td>Now</td>
                <td>
                    <select name="promotion_potential_now" id="promotion_potential_now" class="form-control" {{ $readOnly ? 'disabled' : '' }}>
                        <option value="">-- Select --</option>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                {{ old('promotion_potential_now', $appraisal->promotion_potential_now) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea class="form-control" 
                              name="promotion_now_comment" 
                              rows="2" 
                              placeholder="Comment on promotion potential now."
                              {{ $readOnly ? 'readonly' : '' }}
                    >{{ old('promotion_now_comment', $appraisal->promotion_now_comment) }}</textarea>
                </td>
            </tr>

            {{-- TIME PERIOD: 1-2 YEARS --}}
            <tr>
                <td>1-2 Years</td>
                <td>
                    <select name="promotion_potential_1_2_years" id="promotion_potential_1_2_years" class="form-control" {{ $readOnly ? 'disabled' : '' }}>
                        <option value="">-- Select --</option>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                {{ old('promotion_potential_1_2_years', $appraisal->promotion_potential_1_2_years) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea class="form-control" 
                              name="promotion_1_2_years_comment" 
                              rows="2" 
                              placeholder="Comment on promotion potential in 1-2 years."
                              {{ $readOnly ? 'readonly' : '' }}
                    >{{ old('promotion_1_2_years_comment', $appraisal->promotion_1_2_years_comment) }}</textarea>
                </td>
            </tr>

            {{-- TIME PERIOD: AFTER 2 YEARS --}}
            <tr>
                <td>After 2 Years</td>
                <td>
                    <select name="promotion_potential_after_2_years" id="promotion_potential_after_2_years" class="form-control" {{ $readOnly ? 'disabled' : '' }}>
                        <option value="">-- Select --</option>
                        @foreach($options as $option)
                            <option value="{{ $option }}" 
                                {{ old('promotion_potential_after_2_years', $appraisal->promotion_potential_after_2_years) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea class="form-control" 
                              name="promotion_after_2_years_comment" 
                              rows="2" 
                              placeholder="Comment on promotion potential after 2 years."
                              {{ $readOnly ? 'readonly' : '' }}
                    >{{ old('promotion_after_2_years_comment', $appraisal->promotion_after_2_years_comment) }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<h5 class="mt-5">5(b) Personal Growth</h5>
<p class="text-muted">Discuss the appraisee's needs for training to determine future growth.</p>

<div class="form-group mt-3">
    <label for="personal_growth_comment" class="font-weight-bold">
        Appraiser Comments on Training and Growth Needs:
    </label>
    <textarea class="form-control" 
              id="personal_growth_comment" 
              name="personal_growth_comment" 
              rows="4" 
              placeholder="Enter your assessment of the appraisee's training needs and future growth."
              {{ $readOnly ? 'readonly' : '' }}
    >{{ old('personal_growth_comment', $appraisal->personal_growth_comment) }}</textarea>
</div>

@if($readOnly)
    {{-- Hidden fields to ensure disabled select options are submitted --}}
    <input type="hidden" name="promotion_potential_now" value="{{ $appraisal->promotion_potential_now }}">
    <input type="hidden" name="promotion_potential_1_2_years" value="{{ $appraisal->promotion_potential_1_2_years }}">
    <input type="hidden" name="promotion_potential_after_2_years" value="{{ $appraisal->promotion_potential_after_2_years }}">
@endif