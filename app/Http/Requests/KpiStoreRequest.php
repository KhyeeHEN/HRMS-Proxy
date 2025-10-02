<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KpiStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var \Illuminate\Http\Request $this */

        // Define rules for all fields. Only the first KPI goal is always required.
        // The other goals are optional by default.
        $rules = [
            // department_id is required on create
            'department_id' => 'required|exists:jobtitles,id',
            'unit' => 'nullable|string|max:255',
            //rules for goal array
            'goals' => 'required|array',
            'goals.*.goal' => 'required|string|max:255',
            'goals.*.measurement' => 'required|string|max:255',
            'goals.*.weightage' => 'required|numeric|min:1|max:100', // Note: Minimum 1% weightage
        ];

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        // Custom validation for total weightage MUST BE UPDATED
        $validator->after(function ($validator) {
            /** @var \App\Http\Requests\KpiStoreRequest $this */
            $action = $this->input('action');
            // Assuming 'review' or 'publish' are the actions that require validation
            if (in_array($action, ['review', 'publish', 'resubmit'])) {

                // Get the goals array from the request
                $goals = $this->input('goals', []);
                $totalWeightage = 0;

                foreach ($goals as $goal) {
                    // Check for the weightage value in each goal array item
                    if (isset($goal['weightage']) && is_numeric($goal['weightage'])) {
                        $totalWeightage += (int) $goal['weightage'];
                    }
                }

                if ($totalWeightage !== 100) {
                    $validator->errors()->add('total_weightage', 'The total weightage for all goals must be exactly 100%. Current total: ' . $totalWeightage . '%');
                }
            }
        });
    }
}
