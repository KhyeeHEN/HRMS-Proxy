<!-- resources/views/assets/details.blade.php -->
<div class="modal fade" id="viewAssetModal" tabindex="-1" role="dialog" aria-labelledby="viewAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAssetModalLabel">Asset Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="assetTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="asset-info-tab" data-toggle="tab" href="#asset-info" role="tab">Asset Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="assignee-info-tab" data-toggle="tab" href="#assignee-info" role="tab">Assignee Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="asset-history-tab" data-toggle="tab" href="#asset-history" role="tab">Asset History (Coming Soon)</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="assetTabContent">
                    <div class="tab-pane fade show active" id="asset-info" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr><th>Asset ID</th><td data-field="asset_id"></td></tr>
                                    <tr><th>Asset Name</th><td data-field="asset_name"></td></tr>
                                    <tr><th>Assigned To</th><td data-field="assigned_to"></td></tr>
                                    <tr><th>Department</th><td data-field="department"></td></tr>
                                    <tr><th>Type</th><td data-field="type"></td></tr>
                                    <tr><th>Status</th><td data-field="status"></td></tr>
                                    <tr><th>Model</th><td data-field="model"></td></tr>
                                    <tr><th>S/N No</th><td data-field="sn_no"></td></tr>
                                    <tr><th>CPU</th><td data-field="cpu"></td></tr>
                                    <tr><th>RAM</th><td data-field="ram"></td></tr>
                                    <tr><th>HDD</th><td data-field="hdd"></td></tr>
                                    <tr><th>HDD Balance</th><td data-field="hdd_bal"></td></tr>
                                    <tr><th>HDD2</th><td data-field="hdd2"></td></tr>
                                    
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr><th>HDD2 Balance</th><td data-field="hdd2_bal"></td></tr>
                                    <tr><th>SSD</th><td data-field="ssd"></td></tr>
                                    <tr><th>SSD Balance</th><td data-field="ssd_bal"></td></tr>
                                    <tr><th>OS</th><td data-field="os"></td></tr>
                                    <tr><th>OS Key</th><td data-field="os_key"></td></tr>
                                    <tr><th>Office</th><td data-field="office"></td></tr>
                                    <tr><th>Office Key</th><td data-field="office_key"></td></tr>
                                    <tr><th>Office Login</th><td data-field="office_login"></td></tr>
                                    <tr><th>Antivirus</th><td data-field="antivirus"></td></tr>
                                    <tr><th>Synology</th><td data-field="synology"></td></tr>
                                    <tr><th>DOP</th><td data-field="dop"></td></tr>
                                    <tr><th>Warranty End</th><td data-field="warranty_end"></td></tr>
                                    <tr><th>Remarks</th><td data-field="remarks"></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="assignee-info" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Loading Spinner -->
                                <div class="assignee-loading text-center my-3" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Updating assignee info...</p>
                                </div>
                                <table class="table table-bordered" id="assignee-table" style="display: none;">
                                    <tr><th>Full Name</th><td data-field="employee_name"></td></tr>
                                    <tr><th>Department</th><td data-field="employee_department"></td></tr>
                                    <tr><th>Job Title</th><td data-field="job_title"></td></tr>
                                    <tr><th>Mobile Phone</th><td data-field="mobile_phone"></td></tr>
                                    <tr><th>Work Email</th><td data-field="work_email"></td></tr>
                                </table>
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-assignee-btn" style="display: none;">Remove Assignee</button>
                            </div>
                            <!-- Dropdown & Assign Button (Only visible if not assigned) -->
                            <div class="assign-section mt-3 col-md-12" style="display: none;">
                                <p class="no-assignment-msg text-danger font-italic mb-2">This asset is not assigned yet.</p>
                                <form id="assignForm">
                                    <input type="hidden" name="asset_db_id" id="assign_asset_db_id">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="employee_id" class="mr-2 mb-0">Assign to Employee:</label>
                                        <select class="form-control mr-3" style="max-width: 300px;" id="employee_id" name="employee_id" required>
                                            <option value="">-- Select Employee --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm">Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="asset-history" role="tabpanel">
                        <p class="text-muted">Asset History tab coming soon.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.view-asset-btn', function () {
    const modal = $('#viewAssetModal');
    const assetDbId = $(this).data('asset_db_id');
    const assignedTo = $(this).data('assigned_to');

    // Fill asset DB ID in hidden form input
    $('#assign_asset_db_id').val(assetDbId);

    // Clear previous content
    modal.find('[data-field]').text('');
    $('#assignee-info .table').hide().find('td').text('');
    $('#assignee-info .no-assignment-msg').remove();
    $('.assign-section').hide();
    $('.remove-assignee-btn').hide();

    const fields = [
        'asset_id', 'asset_name', 'assigned_to', 'department', 'type', 'status', 'model', 'sn_no', 'cpu',
        'ram', 'hdd', 'hdd_bal', 'hdd2', 'hdd2_bal', 'ssd', 'ssd_bal',
        'os', 'os_key', 'office', 'office_key', 'office_login', 'antivirus',
        'synology', 'dop', 'warranty_end', 'remarks',
        'employee_name', 'employee_department', 'job_title', 'mobile_phone', 'work_email'
    ];

    // Populate all fields
    fields.forEach(function (field) {
        const value = $(this).data(field);
        modal.find('[data-field="' + field + '"]').text(value ?? 'N/A');
    }.bind(this));

    // Show form if not assigned
    if (!assignedTo || assignedTo === 'N/A' || assignedTo.trim() === '') {
        $('.assign-section').show();
        $('.assign-section').prepend('<p class="no-assignment-msg text-danger font-italic mb-2">This asset is not assigned yet.</p>');
    } else {
        $('#assignee-info .table').show();
        $('.remove-assignee-btn').show();
    }

    modal.modal('show');
});

// Submit form to assign employee
$('#assignForm').on('submit', function (e) {
    e.preventDefault();

    const assetDbId = $('#assign_asset_db_id').val();
    const employeeId = $('#employee_id').val();

    if (!employeeId) {
        alert('Please select an employee.');
        return;
    }

    $.ajax({
        url: '{{ route("assets.assign") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            asset_db_id: assetDbId,
            employee_id: employeeId
        },
        success: function (response) {
            if (response.success) {
                const employee = response.employee;
                const fullName = employee.first_name + ' ' + employee.last_name;

                // Update the table row in index page
                const $rowBtn = $('.view-asset-btn[data-asset_db_id="' + assetDbId + '"]');
                const $row = $rowBtn.closest('tr');
                $row.find('td').eq(9).text(fullName);
                $rowBtn.data('assigned_to', fullName);
                $rowBtn.data('employee_name', fullName);
                $rowBtn.data('employee_department', employee.department);
                $rowBtn.data('job_title', employee.job_title);
                $rowBtn.data('mobile_phone', employee.mobile_phone);
                $rowBtn.data('work_email', employee.work_email);

                // Show spinner and hide other sections
                $('.assign-section').hide();
                $('#assignee-info .no-assignment-msg').remove();
                $('#assignee-info .table').hide();
                $('.remove-assignee-btn').hide();
                $('#assignee-info .assignee-loading').show();

                // Simulate loading effect
                setTimeout(function () {
                    $('#assignee-info [data-field="employee_name"]').text(fullName);
                    $('#assignee-info [data-field="employee_department"]').text(employee.department ?? 'N/A');
                    $('#assignee-info [data-field="job_title"]').text(employee.job_title ?? 'N/A');
                    $('#assignee-info [data-field="mobile_phone"]').text(employee.mobile_phone ?? 'N/A');
                    $('#assignee-info [data-field="work_email"]').text(employee.work_email ?? 'N/A');

                    $('#assignee-info .assignee-loading').fadeOut(200, function () {
                        $('#assignee-info .table').fadeIn();
                        $('.remove-assignee-btn').show();
                    });
                }, 600); // 600ms loading effect

                // Update Asset Info tab
                $('#asset-info [data-field="assigned_to"]').text(fullName);

                // Set update flag
                window.assetUpdated = true;
            }
        },
        error: function () {
            alert('Something went wrong while assigning employee.');
        }
    });
});

// Remove assignee
$(document).on('click', '.remove-assignee-btn', function () {
    if (!confirm('Are you sure you want to remove the assignee from this asset?')) {
        return;
    }

    const assetDbId = $('#assign_asset_db_id').val();

    $.ajax({
        url: '{{ url("assets/unassign") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            asset_db_id: assetDbId
        },
        success: function (response) {
            if (response.success) {
                // Update the table row in index page
                const $rowBtn = $('.view-asset-btn[data-asset_db_id="' + assetDbId + '"]');
                const $row = $rowBtn.closest('tr');
                $row.find('td').eq(9).text('N/A');
                $rowBtn.data('assigned_to', 'N/A');
                $rowBtn.data('employee_name', 'N/A');
                $rowBtn.data('employee_department', 'N/A');
                $rowBtn.data('job_title', 'N/A');
                $rowBtn.data('mobile_phone', 'N/A');
                $rowBtn.data('work_email', 'N/A');

                // Show spinner and hide other sections
                $('#assignee-info .table').hide();
                $('.remove-assignee-btn').hide();
                $('.assign-section').hide();
                $('#assignee-info .no-assignment-msg').remove();
                $('#assignee-info .assignee-loading').show();

                // Simulate loading effect
                setTimeout(function () {
                    $('#assignee-info [data-field="employee_name"]').text('N/A');
                    $('#assignee-info [data-field="employee_department"]').text('N/A');
                    $('#assignee-info [data-field="job_title"]').text('N/A');
                    $('#assignee-info [data-field="mobile_phone"]').text('N/A');
                    $('#assignee-info [data-field="work_email"]').text('N/A');

                    $('#assignee-info .assignee-loading').fadeOut(200, function () {
                        $('.assign-section').prepend('<p class="no-assignment-msg text-danger font-italic mb-2">This asset is not assigned yet.</p>').show();
                    });
                }, 600); // 600ms loading effect

                // Update Asset Info tab
                $('#asset-info [data-field="assigned_to"]').text('N/A');

                // Set update flag
                window.assetUpdated = true;
            }
        },
        error: function () {
            alert('Something went wrong while removing assignee.');
        }
    });
});
</script>