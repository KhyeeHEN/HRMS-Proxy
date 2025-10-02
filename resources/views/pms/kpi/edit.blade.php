@extends('layout')

@section('title', 'Edit KPI')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit KPI for
                    {{ optional($kpi->assignedStaff)->name ?? 'Not Assigned' }}
                </h6>
            </div>
            <div class="card-body">
                @include('partials.error')
                <form action="{{ route('kpi.update', $kpi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $kpi->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="unit">Unit (Optional)</label>
                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter Unit Name"
                            value="{{ old('unit', $kpi->unit) }}">
                    </div>

                    <hr>
                    <h5>Key Goals and Indicator Measurements <button type="button" class="btn btn-sm btn-success ml-2"
                            id="add-goal-btn"><i class="fas fa-plus"></i> Add Goal</button></h5>
                    <div id="goals-container">
                        {{-- Goal inputs will be injected here --}}
                    </div>

                    <div class="form-group mt-3">
                        <label>Total Weightage: <strong id="total-weightage-display">0%</strong></label>
                    </div>

                    {{-- TEMPLATE: Define the template for dynamic goal creation (can be shared or repeated here) --}}
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

                    @if($kpi->status === 'for review')
                        <button type="submit" class="btn btn-primary" name="action" value="review">Save Changes</button>
                    @endif
                    @if ($kpi->status === 'declined')
                        <button type="submit" class="btn btn-primary" name="action" value="resubmit">Resubmit for
                            Review</button>
                    @endif
                    @if($kpi->status === 'draft' || $kpi->status === 'template')
                        <button type="submit" class="btn btn-info" name="action" value="publish">Publish</button>
                    @endif
                    @if($kpi->status !== 'for review' && $kpi->status !== 'declined')
                        <button type="submit" class="btn btn-secondary" name="action" value="draft">Save as Draft</button>
                    @endif
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

            // Goals data from the database (converted to JSON)
            const existingGoals = {!! json_encode($kpi->goals) !!};
            // Goals data from a failed validation attempt
            const oldGoals = {!! json_encode(old('goals', [])) !!};

            const goalsToLoad = oldGoals.length > 0 ? oldGoals : existingGoals;

            function calculateTotalWeightage() {
                // ... (same as create.blade.php) ...
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

            function addGoal(goal = { goal: '', measurement: '', weightage: '' }) {
                const clone = template.content.cloneNode(true).querySelector('.goal-item');
                clone.setAttribute('data-index', goalCount);

                ['goal', 'measurement', 'weightage'].forEach(field => {
                    const input = clone.querySelector(`[name$="[${field}]"]`);
                    input.name = `goals[${goalCount}][${field}]`;
                    input.value = goal[field] || ''; // Populate value
                });

                clone.querySelector('.goal-weightage-input').addEventListener('input', calculateTotalWeightage);
                clone.querySelector('.remove-goal-btn').addEventListener('click', function () {
                    clone.remove();
                    calculateTotalWeightage();
                });

                container.appendChild(clone);
                goalCount++;
            }

            addButton.addEventListener('click', () => addGoal());

            // Load existing goals or old input
            if (goalsToLoad.length > 0) {
                goalsToLoad.forEach(g => addGoal(g));
            } else {
                addGoal(); // Start with one empty goal if none exist
            }

            calculateTotalWeightage();
        });
    </script>
@endpush