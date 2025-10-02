@extends('layout')

@section('title', 'View KPI')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">KPI Details</h6>
                <a href="{{ route('kpi.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to KPI List
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Assigned Staff Member</label>
                            <p class="form-control-static">{{ optional($kpi->assignedStaff)->name ?? 'Not Assigned' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Manager</label>
                            <p class="form-control-static">{{ optional($kpi->manager)->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Department</label>
                            <p class="form-control-static">{{ optional($kpi->department)->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <p class="form-control-static">{{ $kpi->unit_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Year</label>
                            <p class="form-control-static">{{ $kpi->year }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Status</label>
                            <p class="form-control-static">{{ ucwords($kpi->status) }}</p>
                        </div>
                    </div>
                </div>

                <hr>
                <h5>Key Goals and Indicator Measurements (Total Weightage: {{ $kpi->total_weightage }}%)</h5>
                <div class="kpi-goals-list">
                    {{-- Replace the old @for loop with a loop over the goals relationship --}}
                    @foreach($kpi->goals as $i => $goal)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">Goal {{ $i + 1 }} - {{ $goal->goal }}
                                    ({{ $goal->weightage }}%)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Indicator Measurement</label>
                                            <p class="form-control-static">{{ $goal->measurement }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Goal Weightage</label>
                                            <p class="form-control-static">{{ $goal->weightage }}%</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- NEW: Input Form for Staff/Manager --}}
                                @php
                                    $user = Auth::user();
                                    $canStaffInput = $kpi->assigned_to_staff_id === $user->id;
                                    $canManagerComment = $kpi->manager_id === $user->id || in_array($user->access, ['Admin', 'HR']);
                                    $isEditable = in_array($kpi->status, ['accepted', 'archived']);

                                    // Retrieve the SINGLE persistent tracking entry
                                    $currentTracking = $goal->trackings->where('user_id', $kpi->assigned_to_staff_id)->first();
                                @endphp

                                @if($isEditable && ($canStaffInput || $canManagerComment))
                                    <h6 class="mt-4 border-bottom pb-2 clearfix">
                                        Record Goal Performance

                                        {{-- Toggle Button --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary float-right toggle-form-btn"
                                            data-goal-id="{{ $goal->id }}">
                                            <i class="fas fa-edit"></i> Enter Data
                                        </button>
                                    </h6>

                                    {{-- Form Container (Hidden by Default) --}}
                                    <div id="tracking-form-{{ $goal->id }}" style="display: none;"
                                        class="mb-4 p-3 border rounded bg-light">
                                        <form action="{{ route('kpi.goal.track', $goal->id) }}" method="POST">
                                            @csrf

                                            <div class="row">
                                                {{-- Staff Achievement Input --}}
                                                @if($canStaffInput)
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="achievement-{{ $goal->id }}" class="text-success">Your
                                                                Achievement</label>
                                                            <textarea class="form-control" id="achievement-{{ $goal->id }}"
                                                                name="achievement" rows="3"
                                                                placeholder="Describe your achievement towards this goal...">{{ old('achievement', $currentTracking->achievement ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Manager Comment Input --}}
                                                @if($canManagerComment)
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="manager_comment-{{ $goal->id }}" class="text-info">Manager
                                                                Comment</label>
                                                            <textarea class="form-control" id="manager_comment-{{ $goal->id }}"
                                                                name="manager_comment" rows="3"
                                                                placeholder="Add manager comment/feedback on the achievement...">{{ old('manager_comment', $currentTracking->manager_comment ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-save"></i> Save Tracking
                                            </button>
                                        </form>
                                    </div>
                                @endif


                                {{-- NEW: Tracking and Comment Section --}}
                                <h6 class="mt-4 border-bottom pb-2">Tracking Entries</h6>

                                @if($currentTracking)
                                    <div class="row mb-3 p-2 border rounded">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold text-success">Staff Achievement</label>
                                            <p>{{ $currentTracking->achievement ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="font-weight-bold text-info">Manager Comment</label>
                                            <p>{{ $currentTracking->manager_comment ?? 'No comment yet.' }}</p>
                                        </div>
                                        <div class="col-md-12 text-muted small mt-2 pt-2 border-top">
                                            Last Updated: {{ $currentTracking->updated_at->format('M d, Y h:i A') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">No performance record has been saved for this goal yet.</div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find all buttons that trigger the form toggle
            document.querySelectorAll('.toggle-form-btn').forEach(button => {
                button.addEventListener('click', function () {
                    // Get the goal ID from the data attribute
                    const goalId = this.getAttribute('data-goal-id');
                    // Get the form container element
                    const formContainer = document.getElementById(`tracking-form-${goalId}`);
                    const icon = this.querySelector('i');

                    // Toggle visibility
                    if (formContainer.style.display === 'none') {
                        // Show the form
                        formContainer.style.display = 'block';
                        this.innerHTML = '<i class="fas fa-times"></i> Hide Form';
                    } else {
                        // Hide the form
                        formContainer.style.display = 'none';
                        this.innerHTML = '<i class="fas fa-edit"></i> Enter Data';
                    }
                });
            });
        });
    </script>
@endpush