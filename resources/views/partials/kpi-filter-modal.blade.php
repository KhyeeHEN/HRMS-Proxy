{{-- resources/views/partials/kpi-filter-modal.blade.php --}}

<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel"><i class="fas fa-filter"></i> Filter KPIs (Multiple Selection)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kpi.index') }}" method="GET">
                <div class="modal-body">

                    <div class="form-group">
                        <label>Filter by Department</label>
                        <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                            {{-- Department Checkboxes (name="department[]" for array) --}}
                            @foreach($departments as $department)
                                <label class="list-group-item">
                                    <input class="form-check-input mr-1" type="checkbox" name="department[]" id="dept{{ $department->id }}" value="{{ $department->id }}"
                                        {{ is_array($filterDepartment) && in_array($department->id, $filterDepartment) ? 'checked' : '' }}>
                                    {{ $department->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Filter by Status</label>
                        <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                            {{-- Status Checkboxes (name="status[]" for array) --}}
                            @foreach(['draft', 'for review', 'template', 'accepted', 'declined', 'archived'] as $status)
                                <label class="list-group-item">
                                    <input class="form-check-input mr-1" type="checkbox" name="status[]" id="status{{ ucfirst($status) }}" value="{{ $status }}"
                                        {{ is_array($filterStatus) && in_array($status, $filterStatus) ? 'checked' : '' }}>
                                    {{ ucfirst($status) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>