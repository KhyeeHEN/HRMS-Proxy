@extends('layout')

@section('title', 'Past Employees')
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
            <h1 class="h3 mb-0 text-gray-800">Past Employees</h1>
        </div>

        <!-- Content Row -->
        <div class="row mt-4">
            <div class="mb-3">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm ml-2 shadow-sm dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"
                        style="color: #00aeef; background-color: #ffffff; border: 1px solid #00aeef; font-weight:bold;">
                        <i class="fas fa-sort fa-sm mr-1" style="color: #00aeef;"></i> Sort By Date
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ request('sort') == 'desc' ? 'active' : '' }}" href="?sort=desc">Latest</a>
                        <a class="dropdown-item {{ request('sort') == 'asc' ? 'active' : '' }}" href="?sort=asc">Oldest
</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Emp. ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Subsidiary</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Date</th>
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
                                        <td>{{ $employee->departmentName->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->companyStructure->title ?? 'N/A' }}</td>
                                        <td>{{ $employee->employmentStatus->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->status }}</td>
                                        <td>{{ \Carbon\Carbon::parse($employee->termination_date)->format('d/m/Y') }}</td>
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
                                                                                @else                                                {{ 'No SSN' }}
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
        </div>    </div>
@endsection
