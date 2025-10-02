@extends('layout')

@section('title', 'Create KPI')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Create New KPI</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kpi.store') }}" method="POST">
                    @csrf
                    @include('partials.error')
                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <option value="">Select a Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit (Optional)</label>
                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter Unit Name"
                            value="{{ old('unit') }}">
                    </div>

                    <hr>
                    <h5>Key Goals and Indicator Measurements<button type="button" class="btn btn-sm btn-success ml-2"
                            id="add-goal-btn"><i class="fas fa-plus"></i> Add Goal</button></h5>
                    <div id="goals-container">
                        <div class="form-group mt-3">
                            <label>Total Weightage: <strong id="total-weightage-display">0%</strong></label>
                        </div>

                        <template id="goal-template">
                            <div class="goal-item border p-3 mb-3 rounded" data-index="0">
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" class="btn btn-sm btn-danger remove-goal-btn"><i
                                            class="fas fa-times"></i> Remove</button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Key Goal</label>
                                            <input type="text" class="form-control" name="goals[0][goal]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Indicator Measurement</label>
                                            <input type="text" class="form-control" name="goals[0][measurement]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Weightage (%)</label>
                                            <input type="number" class="form-control goal-weightage-input"
                                                name="goals[0][weightage]" min="1" max="100" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="submit" class="btn btn-primary" name="action" value="publish">Submit KPI</button>
                    <button type="submit" class="btn btn-secondary" name="action" value="draft">Save as Draft</button>
                    <a href="{{ route('kpi.index') }}" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('goals-container');
            const template = document.getElementById('goal-template');
            const addButton = document.getElementById('add-goal-btn');
            const totalWeightageDisplay = document.getElementById('total-weightage-display');
            let goalCount = 0;

            function calculateTotalWeightage() {
                let total = 0;
                document.querySelectorAll('.goal-weightage-input').forEach(input => {
                    let value = parseInt(input.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });
                totalWeightageDisplay.textContent = total + '%';
                totalWeightageDisplay.style.color = (total === 100) ? 'green' : 'red';
            }

            function addGoal(goal = {goal: '', measurement: '', weightage: ''}) {
                const clone = template.content.cloneNode(true).querySelector('.goal-item');
                clone.setAttribute('data-index', goalCount);

                // Update input names for the new array index and populate old data on validation error
                ['goal', 'measurement', 'weightage'].forEach(field => {
                    const input = clone.querySelector(`[name$="[${field}]"]`);
                    input.name = `goals[${goalCount}][${field}]`;
                    
                    // Check for old input data (Laravel validation error)
                    const oldGoalData = {!! json_encode(old('goals', [])) !!};
                    if (oldGoalData.length > 0 && oldGoalData[goalCount]) {
                        input.value = oldGoalData[goalCount][field] || '';
                    } else if (goal[field] !== undefined) {
                        // For populating default goals (not needed on create, but good practice)
                        input.value = goal[field];
                    }
                });
                
                // Set up event listeners for the new input
                clone.querySelector('.goal-weightage-input').addEventListener('input', calculateTotalWeightage);
                clone.querySelector('.remove-goal-btn').addEventListener('click', function() {
                    clone.remove();
                    calculateTotalWeightage(); // Recalculate after removal
                });

                container.appendChild(clone);
                goalCount++;
            }

            addButton.addEventListener('click', () => addGoal());

            // Add one default goal on load or re-add old goals if validation failed
            const oldGoals = {!! json_encode(old('goals', [])) !!};
            if (oldGoals.length > 0) {
                oldGoals.forEach(g => addGoal(g));
            } else {
                addGoal(); // Start with one empty goal
            }
            
            calculateTotalWeightage(); // Initial calculation
        });
    </script>
@endpush