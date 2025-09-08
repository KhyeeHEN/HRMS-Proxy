@extends('layout')

@section('title', 'Employees')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .form-input {
        width: 100%;
        /* Ensure the input takes up the full width of its container */
        padding: 10px;
        /* Add padding for better spacing inside the input */
        box-sizing: border-box;
        /* Ensure padding doesn't affect the width */
        border: 1px solid #ccc;
        /* Add a border for consistency */
        border-radius: 4px;
        /* Slightly round the corners */
        font-size: 1rem;
        /* Ensure consistent font size */
        margin-bottom: 10px;
        /* Add some space below each input */
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 0.9rem;
        /* Consistent label font size */
    }
</style>

@section('content')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->routeIs('employees') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('employees') }}">Employees</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('personal') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('personal') }}">Personal</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Content Row -->
    <div class="row">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
            style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addEmployeeModal">
            <i class="fas fa-user-plus fa-sm mr-1" style="color: white;"></i> Add New Employee
        </a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
            style="color: #00aeef; background-color: #ffffff; border: 1px solid #00aeef; font-weight:bold;"
            data-toggle="modal" data-target="#filterEmployeeModal">
            <i class="fas fa-filter fa-sm mr-1" style="color: #00aeef;"></i> Filter
        </a>
    </div>

    <!-- Content Row -->
    <div class="row mt-4">
        <form action="{{ route('employees.search') }}" method="GET"
            class="d-none d-sm-inline-block form-inline mr-auto ml-2 my-2 my-md-0 mw-100 navbar-search"
            style="border: 1px solid #00aeef; border-radius: 7px; width: 250px;">
            <div class="input-group">
                <input type="text" id="searchQuery" name="query" class="form-control bg-light border-0 small"
                    placeholder="Search by Name" aria-label="Search" aria-describedby="basic-addon2"
                    style="height: 32px; font-size: 12px; padding: 5px;">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"
                        style="background-color: #00aeef; border-color: #00aeef; height: 32px; padding: 0 10px;">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Content Row -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Emp. ID</th>
                                <th>Name</th>
                                <th>Birthday</th>
                                <th>Phone No</th>
                                <th>Email</th>
                                <th>Marital Status</th>
                                <th>Dependents</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1; // Initialize the counter variable
                            @endphp
                            @foreach($employees as $employee)
                                                        <tr style="font-size: 14">
                                                            <td>{{ $counter++ }}</td> <!-- Display the counter and increment it -->
                                                            <td>{{ $employee->employee_id }}</td>
                                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($employee->birthday)->format('d-m-Y') }}</td>
                                                            <td>{{ $employee->mobile_phone }}</td>
                                                            <td>{{ $employee->work_email }}</td>
                                                            <td>{{ $employee->marital_status }}</td>
                                                            <td>
                                                                @php
                                                                    $family = $employee->familyDetails;

                                                                    $spouseCount = 0;
                                                                    $childCount = 0;

                                                                    // Check if spouse_name is not null or N/A
                                                                    if ($family && $family->spouse_name && $family->spouse_name !== 'N/A') {
                                                                        $spouseCount = 1;
                                                                    }

                                                                    // Count children based on non-null fields (child1 to child6)
                                                                    for ($i = 1; $i <= 6; $i++) {
                                                                        $childField = 'child' . $i;
                                                                        if ($family && !empty($family->$childField)) {
                                                                            $childCount++;
                                                                        }
                                                                    }

                                                                    $totalDependents = $spouseCount + $childCount;
                                                                @endphp

                                                                {{ $totalDependents }}
                                                            </td>
                                                            <td>
                                                                @if(!empty($employee->ssn_num))
                                                                    @php        
                                                                        $lastSixDigits = substr($employee->ssn_num, -7);
                                                                    @endphp
                                                                    <a
                                                                    href="{{ route('employees.show', [
                                                                        'lastSixDigits' => $lastSixDigits,
                                                                        'employmentStatus' => $employee->employment_status,
                                                                        'firstName' => str_replace(' ', '_', $employee->first_name)
                                                                    ]) }}">View</a>
                                                                @else
                                                                    {{ 'No SSN' }}
                                                                @endif  
                                                          </td>
                                                        </tr>
                            @endforeach
                            @if($employees->isEmpty())
                                <tr>
                                    <td colspan="7">No employees found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="employeeForm" action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal"
                                    role="tab" aria-controls="personal" aria-selected="true">Personal Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="identification-tab" data-toggle="tab" href="#identification"
                                    role="tab" aria-controls="identification" aria-selected="false">Identification</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                aria-labelledby="personal-tab">
                                <div class="form-group mt-4">
                                    <label class="form-label" for="employee_id">Employee ID</label>
                                    <input class="form-input" type="text" id="employee_id" name="employee_id"></br>

                                    <label class="form-label" for="first_name">First Name</label>
                                    <input class="form-input" type="text" id="first_name" name="first_name"></br>

                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input class="form-input" type="text" id="last_name" name="last_name"></br>

                                    <label class="form-label" for="department">Department</label>
                                    <input class="form-input" type="text" id="department" name="department"></br>

                                    <label class="form-label" for="supervisor">Supervisor</label>
                                    <input class="form-input" type="text" id="supervisor" name="supervisor"></br>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="identification" role="tabpanel"
                                aria-labelledby="identification-tab">
                                <!-- Additional fields for identification can go here -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn"
                        style="background-color: white; color: #00aeef; border: 1px solid #00aeef"
                        data-dismiss="modal">Close</button>
                    <button type="button" id="saveChangesBtn" class="btn"
                        style="background-color: #00aeef; color: white;">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="filterEmployeeModal" tabindex="-1" role="dialog"
        aria-labelledby="filterEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterEmployeeModalLabel">Filter By</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('personal.filter') }}" method="GET" id="filterForm">
                        <div class="form-group">
                            <h5>Company</h5>
                            <label><input type="checkbox" name="filters[]" value="ktech" /> Kridentia Tech</label></br>
                            <label><input type="checkbox" name="filters[]" value="kserv" /> Kridentia Integrated
                                Services</label></br>
                            <label><input type="checkbox" name="filters[]" value="kinno" /> Kridentia
                                Innovations</label></br></br>

                            <h5>Employment Status</h5>
                            <label><input type="checkbox" name="filters[]" value="fulltime" /> Full Time</label></br>
                            <label><input type="checkbox" name="filters[]" value="contract" /> Contract</label></br>
                            <label><input type="checkbox" name="filters[]" value="protege" /> Protégé </label></br>
                            <label><input type="checkbox" name="filters[]" value="intern" /> Internship </label></br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn"
                                style="background-color: white; color: #00aeef; border: 1px solid #00aeef"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn" style="background-color: #00aeef; color: white;">Save
                                changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Save changes button click event
    document.getElementById('saveChangesBtn').addEventListener('click', function () {
        var form = document.getElementById('employeeForm');
        var formData = new FormData(form);

        fetch("{{ route('employees.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                // Reload the page or close the modal
                location.reload();
            } else {
                console.error('Failed to save changes');
            }
        }).catch(error => console.error('Error:', error));
    });
</script>
@endsection