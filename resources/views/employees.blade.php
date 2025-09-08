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

                <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

            <!-- Content Row -->
            <div class="row">
                <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                    style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addEmployeeModal">
                    <i class="fas fa-user-plus fa-sm mr-1" style="color: white;"></i> Add New Employee
                </a>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                    style="color: #00aeef; background-color: #ffffff ; border: 1px solid #00aeef; font-weight:bold;"
                    data-toggle="modal" data-target="#filterEmployeeModal">
                    <i class="fas fa-filter fa-sm mr-1" style="color: #00aeef;"></i> Filter
                </a>
                @if(request()->has('filters'))
                <a href="{{ route('employees') }}" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                    style="color: #00aeef; background-color: #ffffff; border: 1px solid #00aeef; font-weight:bold;">
                    <i class="fas fa-times fa-sm mr-1" style="color: #00aeef;"></i> Clear Filter
                </a>
                @endif
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
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Photo</th>
                                        <th>Emp. ID</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Subsidiary</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1; 
                                    @endphp
                                    @foreach($employees as $employee)
                                                            <tr style="font-size: 14">
                                                                <td>{{ $counter++ }}</td> <!-- Display the counter and increment it -->
                                                                <td style="text-align: center; vertical-align: middle;">
                                                                    @if($employee->photo)
                                                                        <img src="data:image/jpeg;base64,{{ base64_encode($employee->photo) }}"
                                                                            alt="Employee Photo"
                                                                            style="width: 100px; height: 100px; object-fit: cover; display: block; margin: 0 auto;">
                                                                    @else
                                                                        <!-- Default Profile Photo SVG -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray"
                                                                            width="100px" height="100px" style="display: block; margin: 0 auto;">
                                                                            <path
                                                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                                        </svg>
                                                                    @endif
                                                                </td>

                                                                <td>{{ $employee->employee_id }}</td>
                                                                <td>
                                                                    {{ $employee->first_name }}
                                                                    @if($employee->ethnicity == 1)
                                                                        @if($employee->gender === 'Male')
                                                                            Bin
                                                                        @elseif($employee->gender === 'Female')
                                                                            Binti
                                                                        @endif
                                                                    @endif
                                                                    {{ $employee->last_name }}
                                                                </td>
                                                                <td>{{ $employee->job_title }}</td>
                                                                <td>{{ $employee->departmentName->name ?? 'N/A' }}</td>
                                                                <td>{{ $employee->companyStructure->title ?? 'N/A' }}</td>
                                                                <td>
                                                                    @if($employee->birthday)
                                                                    @php
                                                                        $birthday = \Carbon\Carbon::parse($employee->birthday);
                                                                        $currentYear = \Carbon\Carbon::now()->year;

            // Calculate the age by subtracting the birth year from the current year
                                                                        $age = $currentYear - $birthday->year;
                                                                    @endphp
                                                                        {{ $age }}
                                                                    @else
                                                                        {{ 'N/A' }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $employee->employmentStatus->name ?? 'N/A' }}</td>
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
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="employeeForm" action="{{ route('employees.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal"
                                            role="tab" aria-controls="personal" aria-selected="true">Personal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="identification-tab" data-toggle="tab" href="#identification"
                                            role="tab" aria-controls="identification" aria-selected="false">Work</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="achievement-tab" data-toggle="tab" href="#achievement" role="tab"
                                            aria-controls="achievement" aria-selected="false">Achievement</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <!-- Personal Information Tab -->
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                        aria-labelledby="personal-tab">
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <!-- Column 1 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Personal Information</h5>
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control mb-3 mb-3" id="first_name"
                                                        name="first_name" required>

                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control mb-3" id="last_name" name="last_name"
                                                        required>

                                                    <label for="ssn_num">MyKad Number</label>
                                                    <input type="text" class="form-control mb-3" id="ssn_num" name="ssn_num"
                                                        required>

                                                    <label for="gender">Gender</label>
                                                    <select class="form-control mb-3" id="gender" name="gender">
                                                        <option value="">Select</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>

                                                    <label for="birthday">Date of Birth</label>
                                                    <input type="date" class="form-control mb-3" id="birthday" name="birthday">

                                                    <label for="nationality">Nationality</label>
                                                    <select class="form-control mb-3" id="nationality" name="nationality">
                                                        <option value="">Select Nationality</option>
                                                        @foreach($nationalities as $nationality)
                                                            <option value="{{ $nationality->id }}">{{ $nationality->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <label for="ethnicity">Ethnicity</label>
                                                    <select class="form-control mb-3" id="ethnicity" name="ethnicity">
                                                        <option value="">Select Ethnicity</option>
                                                        @foreach($ethnicities as $ethnicity)
                                                            <option value="{{ $ethnicity->id }}">{{ $ethnicity->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <label for="marital_status">Marital Status</label>
                                                    <select class="form-control mb-3" id="marital_status" name="marital_status">
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                        <option value="Widowed">Widowed</option>
                                                    </select>

                                                    <label for="dependents">Dependents</label>
                                                    <input type="number" class="form-control mb-3" id="dependents"
                                                        name="dependents" min="0">
                                                </div>

                                                <!-- Column 2 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Contact Information</h5>
                                                    <label for="home_phone">Home Phone</label>
                                                    <input type="text" class="form-control mb-3" id="home_phone"
                                                        name="home_phone">

                                                    <label for="mobile_phone">Mobile Phone</label>
                                                    <input type="text" class="form-control mb-3" id="mobile_phone"
                                                        name="mobile_phone">

                                                    <label for="private_email">Private Email</label>
                                                    <input type="email" class="form-control mb-3" id="private_email"
                                                        name="private_email">
                                                        <h5 style="color:#000000">Employee Contributions</h5>
                                                    <label for="epf_no" class="fw-bold">EPF Number</label>
                                                    <input type="text" class="form-control mb-3" id="epf_no" name="epf_no">

                                                    <label for="socso" class="fw-bold">SOCSO Number</label>
                                                    <input type="text" class="form-control mb-3" id="socso" name="socso">

                                                    <label for="lhdn_no" class="fw-bold">LHDN Number</label>
                                                    <input type="text" class="form-control mb-3" id="lhdn_no" name="lhdn_no">
                                                </div>

                                                <!-- Column 3 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Address Information</h5>
                                                    <label for="address1">Address 1</label>
                                                    <input type="text" class="form-control mb-3" id="address1" name="address1">

                                                    <label for="address2">Address 2</label>
                                                    <input type="text" class="form-control mb-3" id="address2" name="address2">

                                                    <label for="postal_code">Postal Code</label>
                                                    <input type="text" class="form-control mb-3" id="postal_code"
                                                        name="postal_code">

                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control mb-3" id="city" name="city">

                                                    <label for="state">State</label>
                                                    <select class="form-control mb-3" id="state" name="state">
                                                        <option value="">Select State</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="country">Country</label>
                                                    <select class="form-control mb-3" id="country" name="country">
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <h5 class="mt-4" style="color:#000000">Profile Picture</h5>
                                                    <label for="photo">Upload Picture</label>
                                                    <input type="file" class="form-control mb-3" id="photo" name="photo"
                                                        accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Identification Tab -->
                                    <div class="tab-pane fade" id="identification" role="tabpanel"
                                        aria-labelledby="identification-tab">
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <!-- Column 1 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Job Information</h5>
                                                    <label for="employee_id">Employee ID</label>
                                                    <input type="text" class="form-control mb-3" id="employee_id"
                                                        name="employee_id" required>

                                                    <label class="fw-bold" for="company">Subsidiary</label>
                                                    <select class="form-control mb-3" id="company" name="company"
                                                        required>
                                                        <option value="">Select Subsidiary</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->id }}">
                                                                {{ $company->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <label class="fw-bold" for="joined_date">Joined Date</label>
                                                    <input type="date" class="form-control mb-3" id="joined_date"
                                                        name="joined_date" required>
                                                    
                                                    <label class="fw-bold" for="employment_status">Employment Status</label>
                                                    <select class="form-control mb-3" id="employment_status"
                                                        name="employment_status">
                                                        <option value="">Select Status</option>
                                                        @foreach($employment_statuses as $status)
                                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <label class="fw-bold" for="expiry_date">Expiry Date</label>
                                                    <input type="date" class="form-control mb-3" id="expiry_date"
                                                        name="expiry_date">

                                                    <label class="fw-bold" for="job_title">Job Title</label>
                                                    <input type="text" class="form-control mb-3" id="job_title"
                                                        name="job_title" required>

                                                    <label class="fw-bold" for="department">Department</label>
                                                    <select class="form-control mb-3" id="department" name="department">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}">
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Column 2 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Reporting Information</h5>
                                                    <label class="fw-bold" for="supervisor">Reporting Manager</label>
                                                    <input type="text" class="form-control mb-3" id="supervisor"
                                                        name="supervisor" required>

                                                    <label class="fw-bold" for="indirect_supervisor">Supervisor/Team Leader</label>
                                                    <input type="text" class="form-control mb-3" id="indirect_supervisor"
                                                        name="indirect_supervisor" required>

                                                    <label class="fw-bold" for="approver1">Approver 1</label>
                                                    <input type="text" class="form-control mb-3" id="approver1" name="approver1"
                                                        >

                                                    <label class="fw-bold" for="approver2">Approver 2</label>
                                                    <input type="text" class="form-control mb-3" id="approver2" name="approver2"
                                                        >
                                                </div>

                                                <!-- Column 3 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Contact Information</h5>
                                                    <label class="fw-bold" for="work_email">Work Email</label>
                                                    <input type="email" class="form-control mb-3" id="work_email"
                                                        name="work_email" required>

                                                    <label class="fw-bold" for="work_phone">Work Phone</label>
                                                    <input type="text" class="form-control mb-3" id="work_phone"
                                                        name="work_phone" required>

                                                    <h5 style="color:#000000">Work Location</h5>
                                                    <label class="fw-bold" for="branch">Branch</label>
                                                    <input type="text" class="form-control mb-3" id="branch" name="branch"
                                                        >

                                                    <label class="fw-bold" for="work_station">Work Station</label>
                                                    <input type="text" class="form-control mb-3" id="work_station"
                                                        name="work_station">

                                                    <h5 style="color:#000000">Additional</h5>
                                                    <label class="fw-bold" for="notes">Notes</label>
                                                    <textarea class="form-control mb-3" id="notes" name="notes"
                                                        rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Achievement Tab -->
                                    <div class="tab-pane fade" id="achievement" role="tabpanel" aria-labelledby="achievement-tab">
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <!-- Column 1 -->
                                                <div class="col-md-4">
                                                    <h5 style="color:#000000">Achievement/Professional Information</h5>

                                                    <label for="qualification">Qualification</label>
                                                    <input type="text" class="form-control mb-3" id="qualification" name="qualification">

                                                    <label for="experience">Years of Experience</label>
                                                    <input type="text" class="form-control mb-3" id="experience" name="experience">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">Submit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="filterEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="filterEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterEmployeeModalLabel">Filter By</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('employees.filter') }}" method="GET" id="filterForm">
                            <div class="form-group">
                                <h5>Subsidiary</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="ktech" id="ktech">
                                    <label class="form-check-label" for="ktech">Kridentia Tech</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="kserv" id="kserv">
                                    <label class="form-check-label" for="kserv">Kridentia Integrated Services</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="kinno" id="kinno">
                                    <label class="form-check-label" for="kinno">Kridentia Innovations</label>
                                </div>

                                <h5 class="mt-3">Employment Status</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="fulltime" id="fulltime">
                                    <label class="form-check-label" for="fulltime">Full Time</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="contract" id="contract">
                                    <label class="form-check-label" for="contract">Contract</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="protege" id="protege">
                                    <label class="form-check-label" for="protege">Protégé</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="intern" id="intern">
                                    <label class="form-check-label" for="intern">Internship</label>
                                </div>

                                <h5 class="mt-3">Department</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="management" id="management">
                                    <label class="form-check-label" for="management">Management</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="sso" id="sso">
                                    <label class="form-check-label" for="sso">SSO</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="presales" id="presales">
                                    <label class="form-check-label" for="presales">Presales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="sd" id="sd">
                                    <label class="form-check-label" for="sd">Software Development</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="sales" id="sales">
                                    <label class="form-check-label" for="sales">Sales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="pm" id="pm">
                                    <label class="form-check-label" for="pm">Project Manager</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="postsales" id="postsales">
                                    <label class="form-check-label" for="postsales">Post Sales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="filters[]" value="biofis" id="biofis">
                                    <label class="form-check-label" for="biofis">BIOFIS</label>
                                </div>
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
    function validateAndSubmit() {
        const form = document.getElementById('employeeForm');
        const tabs = document.querySelectorAll('.tab-pane');

        // 1. Show all tabs temporarily
        tabs.forEach(tab => {
            tab.classList.add('show', 'active');
        });

        // 2. Try to submit
        if (!form.checkValidity()) {
            // If form invalid, browser will show warning and we scroll to first invalid input
            const firstInvalid = form.querySelector(':invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }

            // 3. Hide tabs again except the one with the invalid input
            tabs.forEach(tab => {
                tab.classList.remove('show', 'active');
            });

            // Find the tab that contains the invalid field and activate it
            const tabPane = firstInvalid.closest('.tab-pane');
            if (tabPane) {
                const tabId = tabPane.id;
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                document.querySelector(`[href="#${tabId}"]`).classList.add('active');
                tabPane.classList.add('show', 'active');
            }

        } else {
            // If form valid, submit
            form.submit();
        }
    }
</script>
@endsection