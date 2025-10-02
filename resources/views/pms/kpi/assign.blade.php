@extends('layout')

@section('title', 'Assign KPI')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Assign KPI to Staff Members</h6>
            </div>
            <div class="card-body">
                @include('partials.error')
                <form action="{{ route('kpi.assign.store', $kpi->id) }}" method="POST">
                    @csrf

                    <p>Assigning KPI: <strong>{{ optional($kpi->department)->name ?? 'N/A' }}
                            @if($kpi->unit)
                                - {{ $kpi->unit }}
                            @endif
                        </strong></p>

                    @if($employees->isEmpty())
                        <div class="alert alert-info">
                            No staff members found in this department or unit to assign this KPI.
                        </div>
                    @else
                        <div class="form-group">
                            <label>Select Staff Members to Assign</label>
                            <div class="list-group">
                                @foreach($employees as $employee)
                                    <label class="list-group-item">
                                        <input type="checkbox" name="staff_members[]" value="{{ $employee->user_id }}"
                                            class="staff-checkbox" {{ in_array($employee->user_id, old('staff_members', [])) ? 'checked' : '' }}>
                                        {{-- Safely retrieve the user's name or fallback to the employee's full name --}}
                                        {{ optional($employee->user)->name ?? $employee->first_name . ' ' . $employee->last_name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div id="weightage-container" class="mt-4" style="display: none;">
                            <h5 class="mb-3">Set Staff-Specific Weightages (Based on Template)</h5>
                            <div class="alert alert-info">
                                Total Weightage for each staff member must equal 100%. Adjust the template's weights if
                                necessary.
                            </div>

                            <ul class="nav nav-tabs" id="staff-tabs" role="tablist">
                                {{-- Tabs will be created here by JS --}}
                            </ul>

                            <div class="tab-content" id="staff-tabs-content">
                                {{-- Tab content will be created here by JS --}}
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="assign-kpi-btn" disabled>Assign KPI</button>
                            <a href="{{ route('kpi.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    {{-- This template is cloned by the JavaScript for each selected staff member's tab --}}
    <template id="staff-goal-template">
        {{-- $kpi here refers to the template KPI being assigned --}}
        @foreach($kpi->goals as $i => $goal)
            <div class="row mb-3 border-bottom pb-3">
                <div class="col-md-5">
                    <label class="font-weight-bold">Goal {{ $i + 1 }}:</label>
                    <p class="form-control-static">{{ $goal->goal }}</p>
                </div>
                <div class="col-md-4">
                    <label>Indicator Measurement:</label>
                    <p class="form-control-static">{{ $goal->measurement }}</p>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="weightage_{{ $i + 1 }}_staff_ID">New Weightage (%)</label>
                        {{-- The name must match the controller logic: weightages[STAFF_ID][weightage_INDEX] --}}
                        <input type="number" id="weightage_{{ $i + 1 }}_staff_ID"
                            name="weightages[STAFF_ID][weightage_{{ $i + 1 }}]" class="form-control staff-weightage" min="1"
                            max="100" {{-- If validation fails, attempt to re-populate the old input, otherwise use the
                            template's weightage --}}
                            value="{{ old('weightages.STAFF_ID.weightage_' . ($i + 1), $goal->weightage) }}" required>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="float-right">Total Weightage: <strong
                        class="staff-total-weightage-display">0%</strong></label>
            </div>
        </div>
    </template>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.staff-checkbox');
            const staffTabs = document.getElementById('staff-tabs');
            const staffTabsContent = document.getElementById('staff-tabs-content');
            const weightageContainer = document.getElementById('weightage-container');
            const assignButton = document.getElementById('assign-kpi-btn');

            // --- UPDATED FUNCTIONS ---

            function calculateTotalWeightageForStaff(staffId) {
                let total = 0;
                const tabPane = document.getElementById(`tab-content-${staffId}`);
                if (!tabPane) return;

                // Select all weightage inputs within the staff member's tab
                tabPane.querySelectorAll('.staff-weightage').forEach(input => {
                    let value = parseInt(input.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                // Update the total weightage display for this staff's tab
                const display = tabPane.querySelector('.staff-total-weightage-display');
                if (display) {
                    display.textContent = total + '%';
                    display.style.color = (total === 100) ? 'green' : 'red';
                }
            }

            function createAndActivateTab(staffId, staffName) {
                // 1. Create or retrieve the tab link
                let tabLink = document.getElementById(`tab-${staffId}`);
                if (!tabLink) {
                    const listItem = document.createElement('li');
                    listItem.classList.add('nav-item');
                    tabLink = document.createElement('a');
                    tabLink.classList.add('nav-link');
                    tabLink.id = `tab-${staffId}`;
                    tabLink.setAttribute('data-toggle', 'tab');
                    tabLink.setAttribute('href', `#tab-content-${staffId}`);
                    tabLink.setAttribute('role', 'tab');
                    tabLink.textContent = staffName;
                    listItem.appendChild(tabLink);
                    staffTabs.appendChild(listItem);
                }

                // 2. Create or retrieve the tab pane
                let tabPane = document.getElementById(`tab-content-${staffId}`);
                if (!tabPane) {
                    tabPane = document.createElement('div');
                    tabPane.classList.add('tab-pane', 'fade', 'p-4');
                    tabPane.id = `tab-content-${staffId}`;
                    tabPane.setAttribute('role', 'tabpanel');
                    tabPane.setAttribute('aria-labelledby', `tab-${staffId}`);
                    staffTabsContent.appendChild(tabPane);
                }

                // 3. Load goals content from the template
                const template = document.getElementById('staff-goal-template');
                let content = template.innerHTML;
                content = content.replace(/STAFF_ID/g, staffId);
                tabPane.innerHTML = content;

                // 4. Attach event listeners
                tabPane.querySelectorAll('.staff-weightage').forEach(input => {
                    input.addEventListener('input', () => calculateTotalWeightageForStaff(staffId));
                });

                // 5. Manually remove active classes from all and apply to the new one for immediate rendering

                // Deactivate all
                staffTabs.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                staffTabsContent.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active', 'show'));

                // Activate the new link and content
                tabLink.classList.add('active');
                tabPane.classList.add('active', 'show');

                calculateTotalWeightageForStaff(staffId);
            }

            // --- EVENT HANDLERS ---

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const staffId = this.value;
                    const staffName = this.parentNode.textContent.trim();

                    if (this.checked) {
                        createAndActivateTab(staffId, staffName);
                    } else {
                        // Remove tab link
                        const tabLink = document.getElementById(`tab-${staffId}`);
                        if (tabLink) {
                            tabLink.parentNode.remove();
                        }

                        // Remove tab content pane
                        const tabPane = document.getElementById(`tab-content-${staffId}`);
                        if (tabPane) {
                            tabPane.remove();
                        }

                        // If the removed tab was active, activate the first remaining tab
                        if (tabLink && tabLink.classList.contains('active')) {
                            const remainingTabs = staffTabs.querySelectorAll('.nav-link');
                            if (remainingTabs.length > 0) {
                                $(remainingTabs[0]).tab('show');
                            }
                        }
                    }

                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    weightageContainer.style.display = anyChecked ? 'block' : 'none';
                    assignButton.disabled = !anyChecked;
                });
            });

            // Recalculate total weightage on input change
            staffTabsContent.addEventListener('input', function (event) {
                if (event.target.classList.contains('staff-weightage')) {
                    // Extract staffId from input name: weightages[STAFF_ID][weightage_X]
                    const match = event.target.name.match(/\[(\d+)\]/);
                    if (match && match[1]) {
                        calculateTotalWeightageForStaff(match[1]);
                    }
                }
            });

            // Re-create and activate tabs for previously checked staff members on page load (e.g., after validation error)
            const initiallyCheckedCheckboxes = document.querySelectorAll('.staff-checkbox:checked');
            if (initiallyCheckedCheckboxes.length > 0) {
                initiallyCheckedCheckboxes.forEach(checkbox => {
                    const staffId = checkbox.value;
                    const staffName = checkbox.parentNode.textContent.trim();
                    createAndActivateTab(staffId, staffName);
                });
                weightageContainer.style.display = 'block';
                assignButton.disabled = false;
            }
        });
    </script>
@endpush