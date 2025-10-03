@extends('layout')

@section('title', 'Assets')

@section('content')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">
<style>
    #viewAssetModal table th {
        background-color: #00aeef;
        color: white; /* Optional: white text for better contrast */
    }
        .content-page:first-of-type {
            display: block;
            /* Show the first page by default */
        }
        /* Adjust font size of search box inside dropdown */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        font-size: 12px; /* or your desired size */
        padding: 4px;
    }

    /* Adjust dropdown option font size */
    .select2-container--default .select2-results__option {
        font-size: 12px; /* or your desired size */
        padding: 6px 12px;
    }

    /* Adjust overall dropdown styling */
    .select2-container--default .select2-selection--single {
        font-size: 12px;
        height: 32px;
        padding: 4px 8px;
        border-radius: 4px;
        border-color: #ccc;
    }

    /* Match height of Select2 selection box to Bootstrap form-control */
    .select2-selection__rendered {
        line-height: 24px !important;
    }

    .select2-selection--single {
        height: 32px !important;
    }

    .select2-selection__arrow {
        height: 32px !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
    color: #aaa;
    font-size: 12px;
}
</style>



<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Company Assets</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

    <!-- Employees Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #176CA1;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1"
                            style="font-size: 1rem; color: #176CA1">
                            Asset
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAssets }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-fw fa-boxes fa-2x" style="color: #176CA1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Companies Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #18ABDD;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1"
                            style="font-size: 1rem; color: #18ABDD">
                            Assigned Asset
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assignedAssets }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x" style="color: #18ABDD"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratio of Permanent:Others -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #1AC9E7;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1"
                            style="font-size: 1rem; color: #1AC9E7">
                            UnAssigned Asset
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unassignedAssets }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-slash fa-2x" style="color: #1AC9E7"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif



    <div class="row">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
            style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addAssetModal">
            <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Asset
        </a>
        <a href="#" class="btn btn-sm ml-2 shadow-sm" data-toggle="modal" data-target="#filterAssetModal"
            style="color: #00aeef; background-color: #ffffff ; border: 1px solid #00aeef; font-weight:bold;">
                <i class="fas fa-filter fa-sm mr-1" style="color: #00aeef;"></i> Filter
            </a>
            @if(request()->has('filters'))
                <a href="{{ route('assets.index') }}" class="btn btn-sm ml-2 shadow-sm"
                style="color: #00aeef; background-color: #ffffff ; border: 1px solid #00aeef; font-weight:bold;">
                    <i class="fas fa-times fa-sm mr-1" style="color: #00aeef;"></i> Clear Filter
                </a>
            @endif
    </div>

    <div class="row mt-4">
        <form action="{{ route('assets.search') }}" method="GET"
            class="form-inline mr-auto ml-2 my-2 my-md-0 mw-100 navbar-search"
            style="border: 1px solid #00aeef; border-radius: 7px; padding: 5px; width: 750px; background-color: white;">
            
            <!-- Search Field -->
            <div class="input-group" style="flex: 1;">
                <input type="text" id="searchQuery" name="query" class="form-control bg-light border-0 small"
                    placeholder="Search by Asset Name, Model or SN No" aria-label="Search"
                    value="{{ request('query') }}"
                    style="height: 32px; font-size: 12px; padding: 5px;">
            </div>

            <!-- Employee Filter -->
            <div class="input-group ml-2" style="flex: 1;">
                <select name="employee_id" id="employeeSelect"
                    class="form-control select2"
                    style="height: 32px; font-size: 12px; padding: 5px;">
                    <option value="">Select Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="input-group-append ml-2">
                <button class="btn btn-primary" type="submit"
                    style="background-color: #00aeef; border-color: #00aeef; height: 32px; padding: 0 10px;">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>

            <!-- Red Clear Filter Button -->
            @if(request()->has('query') || request()->has('employee_id'))
                <div class="input-group-append ml-2">
                    <a href="{{ route('assets.index') }}" class="btn btn-danger btn-sm ml-2 shadow-sm"
                        style="font-weight:bold;">
                        <i class="fas fa-times fa-sm mr-1" style="color:rgb(255, 255, 255);"></i>Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Asset ID</th>
                                <th>Asset Name</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>S/N No</th>
                                <th>Status</th>
                                <th>DOP</th>
                                <th>Department</th>
                                <th>Assigned To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($assets as $asset)
                            <tr style="font-size: 14px;">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $asset->asset_id }}</td>
                                <td>{{ $asset->asset_name }}</td>
                                <td>{{ $asset->category ? $asset->category->name : 'N/A' }}</td>
                                <td>{{ $asset->model }}</td>
                                <td>{{ $asset->sn_no }}</td>
                                <td>{{ $asset->status }}</td>
                                <td>{{ $asset->dop }}</td>
                                <td>{{ $asset->departmentInfo ? $asset->departmentInfo->name : 'N/A' }}</td>
                                <td>{{ $asset->currentAssignment && $asset->currentAssignment->employee 
                                        ? $asset->currentAssignment->employee->first_name . ' ' . $asset->currentAssignment->employee->last_name 
                                        : 'N/A' }}</td>
                                <td>
                                <a href="#" class="btn btn-info btn-sm view-asset-btn"
                                    data-toggle="modal"
                                    data-target="#viewAssetModal"
                                    data-id="{{ $asset->id }}"
                                    data-asset_db_id="{{ $asset->id }}"
                                    data-asset_id="{{ $asset->asset_id }}"
                                    data-asset_name="{{ $asset->asset_name }}"
                                    data-assigned_to="{{ $asset->currentAssignment && $asset->currentAssignment->employee 
                                        ? $asset->currentAssignment->employee->first_name . ' ' . $asset->currentAssignment->employee->last_name 
                                        : 'N/A' }}"
                                    data-department="{{ $asset->departmentInfo ? $asset->departmentInfo->name : 'N/A' }}"
                                    data-type="{{ $asset->category ? $asset->category->name : 'N/A' }}"
                                    data-status="{{ $asset->status }}"
                                    data-model="{{ $asset->model }}"
                                    data-sn_no="{{ $asset->sn_no }}"
                                    data-cpu="{{ $asset->cpu }}"
                                    data-ram="{{ $asset->ram }}"
                                    data-hdd="{{ $asset->hdd }}"
                                    data-hdd_bal="{{ $asset->hdd_bal }}"
                                    data-hdd2="{{ $asset->hdd2 }}"
                                    data-hdd2_bal="{{ $asset->hdd2_bal }}"
                                    data-ssd="{{ $asset->ssd }}"
                                    data-ssd_bal="{{ $asset->ssd_bal }}"
                                    data-os="{{ $asset->os }}"
                                    data-os_key="{{ $asset->os_key }}"
                                    data-office="{{ $asset->office }}"
                                    data-office_key="{{ $asset->office_key }}"
                                    data-office_login="{{ $asset->office_login }}"
                                    data-antivirus="{{ $asset->antivirus }}"
                                    data-synology="{{ $asset->synology }}"
                                    data-dop="{{ $asset->dop }}"
                                    data-warranty_end="{{ $asset->warranty_end }}"
                                    data-remarks="{{ $asset->remarks }}"

                                    data-employee_name="{{ optional(optional($asset->currentAssignment)->employee)->first_name ?? 'N/A' }} {{ optional(optional($asset->currentAssignment)->employee)->last_name ?? '' }}"
                                    data-employee_department="{{ optional(optional(optional($asset->currentAssignment)->employee)->departmentName)->name ?? 'N/A' }}"
                                    data-job_title="{{ optional(optional($asset->currentAssignment)->employee)->job_title ?? 'N/A' }}"
                                    data-mobile_phone="{{ optional(optional($asset->currentAssignment)->employee)->mobile_phone ?? 'N/A' }}"
                                    data-work_email="{{ optional(optional($asset->currentAssignment)->employee)->work_email ?? 'N/A' }}"  
                                >
                                    <i class="fas fa-eye fa-sm" style="color: white;"></i>
                                </a>

                                <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit fa-sm" style="color: white;"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm delete-asset" data-id="{{ $asset->id }}">
                                    <i class="fas fa-trash-alt fa-sm" style="color: white;"></i>
                                </button>
                            </td>
                            </tr>
                            @endforeach
                            @if($assets->isEmpty())
                                <tr>
                                    <td colspan="24">No assets found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Asset Modal -->
{{-- Include asset details modal --}}
@include('assets.details')

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetModalLabel">Add New Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assetForm" action="{{ route('assets.store') }}" method="POST">
                    @csrf
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="asset_id">Asset ID</label>
                                <input type="text" class="form-control mb-3" name="asset_id">

                                <label for="asset_name">Asset Name</label>
                                <input type="text" class="form-control mb-3" name="asset_name" required>

                                <label for="employee_id">Assigned Employee</label>
                                <select class="form-control mb-3" name="employee_id">
                                    <option value="">N/A</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                    @endforeach
                                </select>

                                <label for="department">Department</label>
                                <select class="form-control mb-3" name="department">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>

                                <label for="type">Type</label>
                                <select class="form-control mb-3" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <label for="status">Status</label>
                                <select class="form-control mb-3" name="status">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Idle">Idle</option>
                                    <option value="Damaged">Damaged</option>
                                </select>

                                <label for="model">Model</label>
                                <input type="text" class="form-control mb-3" name="model">

                                <label for="sn_no">S/N No</label>
                                <input type="text" class="form-control mb-3" name="sn_no">

                                <label for="cpu">CPU</label>
                                <input type="text" class="form-control mb-3" name="cpu">

                            </div>
                            <div class="col-md-4">
                                <label for="ram">RAM</label>
                                <input type="text" class="form-control mb-3" name="ram">

                                <label for="hdd">HDD</label>
                                <input type="text" class="form-control mb-3" name="hdd">

                                <label for="hdd_bal">HDD Balance</label>
                                <input type="text" class="form-control mb-3" name="hdd_bal">

                                <label for="hdd2">HDD2</label>
                                <input type="text" class="form-control mb-3" name="hdd2">

                                <label for="hdd2_bal">HDD2 Balance</label>
                                <input type="text" class="form-control mb-3" name="hdd2_bal">

                                <label for="ssd">SSD</label>
                                <input type="text" class="form-control mb-3" name="ssd">

                                <label for="ssd_bal">SSD Balance</label>
                                <input type="text" class="form-control mb-3" name="ssd_bal">

                                <label for="os">OS</label>
                                <input type="text" class="form-control mb-3" name="os">

                                <label for="os_key">OS Key</label>
                                <input type="text" class="form-control mb-3" name="os_key">

                                

                            </div>
                            <div class="col-md-4">
                                <label for="office">Office</label>
                                <input type="text" class="form-control mb-3" name="office">

                                <label for="office_key">Office Key</label>
                                <input type="text" class="form-control mb-3" name="office_key">

                                <label for="office_login">Office Login</label>
                                <input type="text" class="form-control mb-3" name="office_login">

                                <label for="antivirus">Antivirus</label>
                                <input type="text" class="form-control mb-3" name="antivirus">

                                <label for="synology">Synology</label>
                                <input type="text" class="form-control mb-3" name="synology">

                                <label for="dop">Date of Purchase (Year)</label>
                                <input type="number" class="form-control mb-3" name="dop" min="2000" max="{{ date('Y') }}">

                                <label for="warranty_end">Warranty End</label>
                                <input type="text" class="form-control mb-3" name="warranty_end">

                                <label for="remarks">Remarks</label>
                                <textarea class="form-control mb-3" name="remarks" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="filterAssetModal" tabindex="-1" role="dialog" aria-labelledby="filterAssetModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('assets.filter') }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Assets</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Category</h6>
                    @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[category][]" value="{{ $category->id }}" id="cat{{ $category->id }}">
                            <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach

                    <h6 class="mt-3">Department</h6>
                    @foreach($departments as $dept)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[department][]" value="{{ $dept->id }}" id="dept{{ $dept->id }}">
                            <label class="form-check-label" for="dept{{ $dept->id }}">{{ $dept->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: white; color: #00aeef; border: 1px solid #00aeef" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #00aeef; color: white;">Apply</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#employeeSelect').select2({
            placeholder: "Select Employee",
            allowClear: true,
            width: '100%' // or '100%' if wrapped in flex div
        });
        // Add placeholder to the internal search field
        $('#employeeSelect').on('select2:open', function () {
            // Wait for search input to be rendered
            setTimeout(function () {
                $('.select2-container--open .select2-search__field').attr('placeholder', 'Search employee');
            }, 0);
        });
    });
</script>
<script>
$(document).ready(function () {
    $('.delete-asset').on('click', function () {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this asset?')) {
            $.ajax({
                url: '{{ route("assets.destroy", ["id" => ":id"]) }}'.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    if (response.success) {
                        alert('Asset deleted successfully.');
                        location.reload(); // Reload the page to reflect changes
                    }
                },
                error: function (xhr) {
                    alert('Error deleting asset. Please try again.');
                    console.log(xhr.responseText); // Log error for debugging
                }
            });
        }
    });
});
</script>

<script>
    // Auto-hide success message
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) alert.style.display = 'none';
    }, 3000); // hides after 4 seconds
</script>
<script>
$(document).on('hidden.bs.modal', '#viewAssetModal', function () {
    if (window.assetUpdated) {
        // optional: clear flag so next close without changes doesn’t reload
        window.assetUpdated = false;

        // force‑cached reload (uses cache if possible, so already “faster”)
        location.reload(false);
        // OR comment the line above to skip any reload—UI is already updated
    }
});
</script>
@endsection

@extends('layout')

@section('title', 'Assets')

@section('content')
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <style>
        #viewAssetModal table th {
            background-color: #00aeef;
            color: white;
            /* Optional: white text for better contrast */
        }

        .content-page:first-of-type {
            display: block;
            /* Show the first page by default */
        }

        /* Adjust font size of search box inside dropdown */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            font-size: 12px;
            /* or your desired size */
            padding: 4px;
        }

        /* Adjust dropdown option font size */
        .select2-container--default .select2-results__option {
            font-size: 12px;
            /* or your desired size */
            padding: 6px 12px;
        }

        /* Adjust overall dropdown styling */
        .select2-container--default .select2-selection--single {
            font-size: 12px;
            height: 32px;
            padding: 4px 8px;
            border-radius: 4px;
            border-color: #ccc;
        }

        /* Match height of Select2 selection box to Bootstrap form-control */
        .select2-selection__rendered {
            line-height: 24px !important;
        }

        .select2-selection--single {
            height: 32px !important;
        }

        .select2-selection__arrow {
            height: 32px !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
            color: #aaa;
            font-size: 12px;
        }
    </style>



    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Company Assets</h1>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Employees Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #176CA1;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                    style="font-size: 1rem; color: #176CA1">
                                    Asset
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAssets }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fa-boxes fa-2x" style="color: #176CA1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Companies Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #18ABDD;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                    style="font-size: 1rem; color: #18ABDD">
                                    Assigned Asset
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assignedAssets }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x" style="color: #18ABDD"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ratio of Permanent:Others -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #1AC9E7;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                    style="font-size: 1rem; color: #1AC9E7">
                                    UnAssigned Asset
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unassignedAssets }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-slash fa-2x" style="color: #1AC9E7"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif



        <div class="row">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addAssetModal">
                <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Asset
            </a>
            <a href="#" class="btn btn-sm ml-2 shadow-sm" data-toggle="modal" data-target="#filterAssetModal"
                style="color: #00aeef; background-color: #ffffff ; border: 1px solid #00aeef; font-weight:bold;">
                <i class="fas fa-filter fa-sm mr-1" style="color: #00aeef;"></i> Filter
            </a>
            @if(request()->has('filters'))
                <a href="{{ route('assets.index') }}" class="btn btn-sm ml-2 shadow-sm"
                    style="color: #00aeef; background-color: #ffffff ; border: 1px solid #00aeef; font-weight:bold;">
                    <i class="fas fa-times fa-sm mr-1" style="color: #00aeef;"></i> Clear Filter
                </a>
            @endif
        </div>

        <div class="row mt-4">
            <form action="{{ route('assets.search') }}" method="GET"
                class="form-inline mr-auto ml-2 my-2 my-md-0 mw-100 navbar-search"
                style="border: 1px solid #00aeef; border-radius: 7px; padding: 5px; width: 750px; background-color: white;">

                <!-- Search Field -->
                <div class="input-group" style="flex: 1;">
                    <input type="text" id="searchQuery" name="query" class="form-control bg-light border-0 small"
                        placeholder="Search by Asset Name, Model or SN No" aria-label="Search"
                        value="{{ request('query') }}" style="height: 32px; font-size: 12px; padding: 5px;">
                </div>

                <!-- Employee Filter -->
                <div class="input-group ml-2" style="flex: 1;">
                    <select name="employee_id" id="employeeSelect" class="form-control select2"
                        style="height: 32px; font-size: 12px; padding: 5px;">
                        <option value="">Select Employees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div class="input-group-append ml-2">
                    <button class="btn btn-primary" type="submit"
                        style="background-color: #00aeef; border-color: #00aeef; height: 32px; padding: 0 10px;">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>

                <!-- Red Clear Filter Button -->
                @if(request()->has('query') || request()->has('employee_id'))
                    <div class="input-group-append ml-2">
                        <a href="{{ route('assets.index') }}" class="btn btn-danger btn-sm ml-2 shadow-sm"
                            style="font-weight:bold;">
                            <i class="fas fa-times fa-sm mr-1" style="color:rgb(255, 255, 255);"></i>Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Asset ID</th>
                                    <th>Asset Name</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>S/N No</th>
                                    <th>Status</th>
                                    <th>DOP</th>
                                    <th>Department</th>
                                    <th>Assigned To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($assets as $asset)
                                                        <tr style="font-size: 14px;">
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ $asset->asset_id }}</td>
                                                            <td>{{ $asset->asset_name }}</td>
                                                            <td>{{ $asset->category ? $asset->category->name : 'N/A' }}</td>
                                                            <td>{{ $asset->model }}</td>
                                                            <td>{{ $asset->sn_no }}</td>
                                                            <td>{{ $asset->status }}</td>
                                                            <td>{{ $asset->dop }}</td>
                                                            <td>{{ $asset->departmentInfo ? $asset->departmentInfo->name : 'N/A' }}</td>
                                                            <td>{{ $asset->currentAssignment && $asset->currentAssignment->employee
                                    ? $asset->currentAssignment->employee->first_name . ' ' . $asset->currentAssignment->employee->last_name
                                    : 'N/A' }}</td>
                                                            <td>
                                                                <a href="#" class="btn btn-info btn-sm view-asset-btn" data-toggle="modal"
                                                                    data-target="#viewAssetModal" data-id="{{ $asset->id }}"
                                                                    data-asset_db_id="{{ $asset->id }}" data-asset_id="{{ $asset->asset_id }}"
                                                                    data-asset_name="{{ $asset->asset_name }}" data-assigned_to="{{ $asset->currentAssignment && $asset->currentAssignment->employee
                                    ? $asset->currentAssignment->employee->first_name . ' ' . $asset->currentAssignment->employee->last_name
                                    : 'N/A' }}"
                                                                    data-department="{{ $asset->departmentInfo ? $asset->departmentInfo->name : 'N/A' }}"
                                                                    data-type="{{ $asset->category ? $asset->category->name : 'N/A' }}"
                                                                    data-status="{{ $asset->status }}" data-model="{{ $asset->model }}"
                                                                    data-sn_no="{{ $asset->sn_no }}" data-cpu="{{ $asset->cpu }}"
                                                                    data-ram="{{ $asset->ram }}" data-hdd="{{ $asset->hdd }}"
                                                                    data-hdd_bal="{{ $asset->hdd_bal }}" data-hdd2="{{ $asset->hdd2 }}"
                                                                    data-hdd2_bal="{{ $asset->hdd2_bal }}" data-ssd="{{ $asset->ssd }}"
                                                                    data-ssd_bal="{{ $asset->ssd_bal }}" data-os="{{ $asset->os }}"
                                                                    data-os_key="{{ $asset->os_key }}" data-office="{{ $asset->office }}"
                                                                    data-office_key="{{ $asset->office_key }}"
                                                                    data-office_login="{{ $asset->office_login }}"
                                                                    data-antivirus="{{ $asset->antivirus }}" data-synology="{{ $asset->synology }}"
                                                                    data-dop="{{ $asset->dop }}" data-warranty_end="{{ $asset->warranty_end }}"
                                                                    data-remarks="{{ $asset->remarks }}"
                                                                    data-employee_name="{{ optional(optional($asset->currentAssignment)->employee)->first_name ?? 'N/A' }} {{ optional(optional($asset->currentAssignment)->employee)->last_name ?? '' }}"
                                                                    data-employee_department="{{ optional(optional(optional($asset->currentAssignment)->employee)->departmentName)->name ?? 'N/A' }}"
                                                                    data-job_title="{{ optional(optional($asset->currentAssignment)->employee)->job_title ?? 'N/A' }}"
                                                                    data-mobile_phone="{{ optional(optional($asset->currentAssignment)->employee)->mobile_phone ?? 'N/A' }}"
                                                                    data-work_email="{{ optional(optional($asset->currentAssignment)->employee)->work_email ?? 'N/A' }}">
                                                                    <i class="fas fa-eye fa-sm" style="color: white;"></i>
                                                                </a>

                                                                <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-edit fa-sm" style="color: white;"></i>
                                                                </a>

                                                                <button type="button" class="btn btn-danger btn-sm delete-asset"
                                                                    data-id="{{ $asset->id }}">
                                                                    <i class="fas fa-trash-alt fa-sm" style="color: white;"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                @endforeach
                                @if($assets->isEmpty())
                                    <tr>
                                        <td colspan="24">No assets found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Asset Modal -->
    {{-- Include asset details modal --}}
    @include('assets.details')

    <!-- Add Asset Modal -->
    <div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" aria-labelledby="addAssetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAssetModalLabel">Add New Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assetForm" action="{{ route('assets.store') }}" method="POST">
                        @csrf
                        <div class="form-group mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="asset_id">Asset ID</label>
                                    <input type="text" class="form-control mb-3" name="asset_id">

                                    <label for="asset_name">Asset Name</label>
                                    <input type="text" class="form-control mb-3" name="asset_name" required>

                                    <label for="employee_id">Assigned Employee</label>
                                    <select class="form-control mb-3" name="employee_id">
                                        <option value="">N/A</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name }}
                                                {{ $employee->last_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label for="department">Department</label>
                                    <select class="form-control mb-3" name="department">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>

                                    <label for="type">Type</label>
                                    <select class="form-control mb-3" name="type" required>
                                        <option value="">Select Type</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    <label for="status">Status</label>
                                    <select class="form-control mb-3" name="status">
                                        <option value="">Select Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Idle">Idle</option>
                                        <option value="Damaged">Damaged</option>
                                    </select>

                                    <label for="model">Model</label>
                                    <input type="text" class="form-control mb-3" name="model">

                                    <label for="sn_no">S/N No</label>
                                    <input type="text" class="form-control mb-3" name="sn_no">

                                    <label for="cpu">CPU</label>
                                    <input type="text" class="form-control mb-3" name="cpu">

                                </div>
                                <div class="col-md-4">
                                    <label for="ram">RAM</label>
                                    <input type="text" class="form-control mb-3" name="ram">

                                    <label for="hdd">HDD</label>
                                    <input type="text" class="form-control mb-3" name="hdd">

                                    <label for="hdd_bal">HDD Balance</label>
                                    <input type="text" class="form-control mb-3" name="hdd_bal">

                                    <label for="hdd2">HDD2</label>
                                    <input type="text" class="form-control mb-3" name="hdd2">

                                    <label for="hdd2_bal">HDD2 Balance</label>
                                    <input type="text" class="form-control mb-3" name="hdd2_bal">

                                    <label for="ssd">SSD</label>
                                    <input type="text" class="form-control mb-3" name="ssd">

                                    <label for="ssd_bal">SSD Balance</label>
                                    <input type="text" class="form-control mb-3" name="ssd_bal">

                                    <label for="os">OS</label>
                                    <input type="text" class="form-control mb-3" name="os">

                                    <label for="os_key">OS Key</label>
                                    <input type="text" class="form-control mb-3" name="os_key">



                                </div>
                                <div class="col-md-4">
                                    <label for="office">Office</label>
                                    <input type="text" class="form-control mb-3" name="office">

                                    <label for="office_key">Office Key</label>
                                    <input type="text" class="form-control mb-3" name="office_key">

                                    <label for="office_login">Office Login</label>
                                    <input type="text" class="form-control mb-3" name="office_login">

                                    <label for="antivirus">Antivirus</label>
                                    <input type="text" class="form-control mb-3" name="antivirus">

                                    <label for="synology">Synology</label>
                                    <input type="text" class="form-control mb-3" name="synology">

                                    <label for="dop">Date of Purchase (Year)</label>
                                    <input type="number" class="form-control mb-3" name="dop" min="2000"
                                        max="{{ date('Y') }}">

                                    <label for="warranty_end">Warranty End</label>
                                    <input type="text" class="form-control mb-3" name="warranty_end">

                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control mb-3" name="remarks" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="filterAssetModal" tabindex="-1" role="dialog" aria-labelledby="filterAssetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('assets.filter') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Assets</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Category</h6>
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[category][]"
                                    value="{{ $category->id }}" id="cat{{ $category->id }}">
                                <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach

                        <h6 class="mt-3">Department</h6>
                        @foreach($departments as $dept)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="filters[department][]"
                                    value="{{ $dept->id }}" id="dept{{ $dept->id }}">
                                <label class="form-check-label" for="dept{{ $dept->id }}">{{ $dept->name }}</label>
                            </div>
                        @endforeach

                        <h6 class="mt-3">Status</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[status][]" value="Active"
                                id="statusActive">
                            <label class="form-check-label" for="statusActive">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filters[status][]" value="Idle"
                                id="statusIdle">
                            <label class="form-check-label" for="statusIdle">Idle</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn"
                            style="background-color: white; color: #00aeef; border: 1px solid #00aeef"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn" style="background-color: #00aeef; color: white;">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            $('#employeeSelect').select2({
                placeholder: "Select Employee",
                allowClear: true,
                width: '100%' // or '100%' if wrapped in flex div
            });
            // Add placeholder to the internal search field
            $('#employeeSelect').on('select2:open', function () {
                // Wait for search input to be rendered
                setTimeout(function () {
                    $('.select2-container--open .select2-search__field').attr('placeholder', 'Search employee');
                }, 0);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.delete-asset').on('click', function () {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this asset?')) {
                    $.ajax({
                        url: '{{ route("assets.destroy", ["id" => ":id"]) }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function (response) {
                            if (response.success) {
                                alert('Asset deleted successfully.');
                                location.reload(); // Reload the page to reflect changes
                            }
                        },
                        error: function (xhr) {
                            alert('Error deleting asset. Please try again.');
                            console.log(xhr.responseText); // Log error for debugging
                        }
                    });
                }
            });
        });
    </script>

    <script>
        // Auto-hide success message
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.style.display = 'none';
        }, 3000); // hides after 4 seconds
    </script>
    <script>
        $(document).on('hidden.bs.modal', '#viewAssetModal', function () {
            if (window.assetUpdated) {
                // optional: clear flag so next close without changes doesn’t reload
                window.assetUpdated = false;

                // force‑cached reload (uses cache if possible, so already “faster”)
                location.reload(false);
                // OR comment the line above to skip any reload—UI is already updated
            }
        });
    </script>
@endsection