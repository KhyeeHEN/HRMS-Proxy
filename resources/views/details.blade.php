@extends('layout')

@section('title', 'Employee Details')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .personal-information {
        border: 1px solid #FFC6C6;
        padding: 10px;
        margin-bottom: 20px;
        background: linear-gradient(to right, #00aeef, #002244);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .personal-information p {
        font-family: "Lucida Console", "Courier New", monospace;
        font-size: 1.0rem;
        color: #fff;
        font-weight: bold;
        text-align: left;
    }

    .label-box-container {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
        width: calc(50% - 10px);
    }

    .label-box {
        flex: 1 1 calc(30% - 10px);
        /* Label takes 30% of the space with some flexibility */
        padding: 10px;
        color: #000;
        background-color: #D1E9F6;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-weight: bold;
        box-sizing: border-box;
        margin: 0;
        border: 1px solid #ccc;
        min-width: 150px;
        /* Set a minimum width that makes sense for the label */
    }

    .input-field {
        flex: 1 1 calc(70% - 10px);
        /* Input takes 70% of the space with some flexibility */
        padding: 8px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #595959;
        box-sizing: border-box;
        margin: 0;
        min-width: 250px;
        /* Set a minimum width that makes sense for the input field */
    }

    .col-span-2.grid.grid-cols-2.gap-2 {
        display: flex;
        flex-wrap: wrap;
        column-gap: 20px;
        /* Add a gap between the two "things" (label and input pairs) */
    }

    @media (max-width: 1024px) {
        .label-box-container {
            width: 100%;
            margin-bottom: 10px;
        }

        .label-box,
        .input-field {
            flex: 1 1 100%;
            min-width: 100%;
            /* Ensure both label and input take up full width on medium screens */
        }
    }

    .profile-image img {
        width: 150px;
        height: 160px;
        float: right;
    }

    .logo-container img {
        float: left;
    }

    .container {
        max-width: 800px;
        /* Set max-width for the container */
        margin: 0 auto;
        /* Center the container */
        padding: 20px;
        /* Add padding to the container */
    }

    .bg-white {
        background-color: #ffffff;
    }

    .btn:hover {
        background-color: #e0f7ff;
        /* Optional: Light background on hover */
    }

    .nav-tabs {
        min-width: 1125px;
        margin-bottom: 20px;
        justify-content: center;
    }

    .nav-tabs .nav-item {
        margin-right: 20px;
    }

    .tab-content {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
    }

    /* Hide all sections by default */
    .tab-pane {
        min-width: 1125px;
        display: none;
    }

    /* Show the active section */
    .tab-pane.active {
        display: block;
    }

    .upload-form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    .form-input:focus {
        border-color: #00aeef;
        outline: none;
    }

    .submit-button {
        width: 100%;
        padding: 12px;
        background-color: #00aeef;
        color: white;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-button:hover {
        background-color: #008dcf;
    }

    .document-list {
        list-style: none;
        padding-left: 0;
        margin: 0 0 20px 0;
    }

    .document-list li {
        background-color: #f0f6fa;
        border: 1px solid #ccc;
        padding: 10px 15px;
        margin-bottom: 8px;
        border-radius: 6px;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-link {
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
    }

    .file-link:hover {
        text-decoration: underline;
    }

    .file-reference {
        color: #555;
        font-style: italic;
        font-size: 13px;
        margin-left: 10px;
    }

    .file-info {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .search-box {
        padding: 6px 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 200px;
    }

    .search-box:focus {
        outline: none;
        border-color: #00aeef;
        box-shadow: 0 0 5px rgba(0, 174, 239, 0.5);
    }
</style>

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Employee Profile</h1>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <ul class='p-2'>
            <!-- Count dependents -->
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

            <!-- Start of details form -->
            <form class="grid gap-4 p-3 rounded shadow-md" style="background-color: #ffffff;">
                <div class="col-span-2 text-left">
                    <div class="profile-container">
                        <!-- Employee Photo Section -->
                        <div class="profile-image"
                            style="float: left; border-radius: 50%; width: 150px; height: 150px; overflow: hidden; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; margin: 20px">
                            @if($employee->photo)
                                <img src="data:image/jpeg;base64,{{ base64_encode($employee->photo) }}" alt="Employee Photo"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <!-- Default Profile Photo SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray" width="100%"
                                    height="100%">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>

                            @endif
                        </div>
                    </div>
                    <h3 class="mt-5 ml-2">{{ $employee->first_name }}
                        @if($employee->ethnicity == 1)
                            @if($employee->gender === 'Male')
                                Bin
                            @elseif($employee->gender === 'Female')
                                Binti
                            @endif
                        @endif
                        {{ $employee->last_name }}
                    </h3> </br>
                    <h6 class="ml-2" style="margin-bottom: 10px;">
                        <i class="fa-solid fa-phone mr-2"></i> {{ $employee->mobile_phone }}
                    </h6>
                    <h6 class="ml-2">
                        <i class="fa-solid fa-envelope mr-2"></i>
                        {{ $employee->private_email ?? $employee->work_email }}
                    </h6>
                </div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs mt-2" id="profileTabs" role="tablist">
                @if(in_array(auth()->user()->access, ['Admin', 'HR']))
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab"
                            aria-controls="personal" aria-selected="true">Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="work-tab" data-toggle="tab" href="#work" role="tab" aria-controls="work"
                            aria-selected="false">Work</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="family-tab" data-toggle="tab" href="#family" role="tab"
                            aria-controls="family" aria-selected="false">Family</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="achievement-tab" data-toggle="tab" href="#achievement" role="tab"
                            aria-controls="achievement" aria-selected="false">Achievement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab"
                            aria-controls="document" aria-selected="false">Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab"
                            aria-controls="salary" aria-selected="false">Salary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="kpi-tab" data-toggle="tab" href="#kpi" role="tab" aria-controls="kpi"
                            aria-selected="false">KPI</a>
                    </li>
                    @endif
                    @if(in_array(auth()->user()->access, ['Admin', 'HR', 'Technical']))
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') === 'asset' ? 'active' : '' }}" id="asset-tab" data-toggle="tab" href="#asset" role="tab"
                            aria-controls="asset" aria-selected="{{ in_array(auth()->user()->access, ['Technical']) ? 'true' : 'false' }}">Asset</a>
                    </li>
                    @endif
                </ul></br>
                <!-- Tab content -->
                <div class="tab-content" id="profileContent">
                @if(in_array(auth()->user()->access, ['Admin', 'HR']))
                    <!-- Personal Info -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">PERSONAL INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Personal Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">First Name:</label>
                                <input type="text" value="{{ $employee->first_name }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Birthday:</label>
                                <input type="text"
                                    value="{{ $employee->birthday ? $employee->birthday->format('d-m-Y') : '' }}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Last Name:</label>
                                <input type="text" value="{{ $employee->last_name }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Nationality:</label>
                                <input type="text" value="{{ $employee->national->name ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">MyKad Number:</label>
                                <input type="text" value="{{ $employee->ssn_num }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Ethnicity:</label>
                                <input type="text" value="{{ $employee->ethnicityName->name ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Gender:</label>
                                <input type="text" value="{{ $employee->gender }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Marital Status:</label>
                                <input type="text" value="{{ $employee->marital_status }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Age:</label>
                                <input type="text"
                                    value="@if($employee->birthday){{ \Carbon\Carbon::now()->year - \Carbon\Carbon::parse($employee->birthday)->year }}@else N/A @endif"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Dependents:</label>
                                <input type="text" value="{{ $totalDependents }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Contact Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Home Phone:</label>
                                <input type="text" value="{{ $employee->home_phone }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Private Email:</label>
                                <input type="text" value="{{ $employee->private_email }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Mobile Phone:</label>
                                <input type="text" value="{{ $employee->mobile_phone }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Address Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Address 1:</label>
                                <input type="text" value="{{ $employee->address1 }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">City:</label>
                                <input type="text" value="{{ $employee->city }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Address 2:</label>
                                <input type="text" value="{{ $employee->address2 }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">State:</label>
                                <input type="text" value="{{ $employee->stateName->name ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Postal Code:</label>
                                <input type="text" value="{{ $employee->postal_code }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Country:</label>
                                <input type="text" value="{{ $employee->countryName->name ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Employee Contributions</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">EPF Number:</label>
                                <input type="text" value="{{ $employee->epf_no }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">SOCSO Number:</label>
                                <input type="text" value="{{ $employee->socso }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">LHDN Number:</label>
                                <input type="text" value="{{ $employee->lhdn_no }}" class="input-field" readonly>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->access, ['Admin', 'HR']))
                    <!-- Work Info -->
                    <div class="tab-pane fade show active" id="work" role="tabpanel" aria-labelledby="work-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">WORK INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Job Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Employee ID:</label>
                                <input type="text" value="{{ $employee->employee_id }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Title:</label>
                                <input type="text" value="{{ $employee->job_title }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Subsidiary:</label>
                                <input type="text" value="{{ $employee->companyStructure->title ?? ' ' }}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Department:</label>
                                <input type="text" value="{{ $employee->department }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Employment Status:</label>
                                <input type="text" value="{{ $employee->employmentStatus->name ?? 'N/A' }}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Joined Date:</label>
                                <input type="text"
                                    value="{{ $employee->joined_date ? $employee->joined_date->format('d-m-Y') : ''}}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Status:</label>
                                <input type="text" value="{{ $employee->status }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Resigned Date:</label>
                                <input type="text"
                                    value="{{ $employee->termination_date ? $employee->termination_date->format('d-m-Y') : 'N/A' }}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Pay Grade:</label>
                                <input type="text" value="{{ $employee->payGrade->name ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Reporting Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Reporting Manager:</label>
                                <input type="text" value="{{ $employee->supervisor ?? 'N/A' }}" class="input-field"
                                    readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Supervisor/Team Leader:</label>
                                <input type="text" value="{{ $employee->indirect_supervisors ?? 'N/A' }}"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Approver 1:</label>
                                <input type="text" value="{{ $employee->approver1 }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Approver 2:</label>
                                <input type="text" value="{{ $employee->approver2 }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Contact Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Email:</label>
                                <input type="text" value="{{ $employee->work_email }}" class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Phone:</label>
                                <input type="text" value="{{ $employee->work_phone }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Work Location</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Branch:</label>
                                <input type="text" value="{{ $employee->branch }}" class="input-field" readonly>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Station:</label>
                                <input type="text" value="{{ $employee->work_station_id }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Additional</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Notes:</label>
                                <input type="text" value="{{ $employee->notes }}" class="input-field" readonly>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->access, ['Admin', 'HR']))
                    <!-- Family info -->
                    @php
                        $hasSpouse = !empty($employee->familyDetails->spouse_ic);
                        $hasChild = !empty($employee->familyDetails->child1);
                        $hasEmergency = collect([
                            $employee->familyDetails->contact1_name ?? null,
                            $employee->familyDetails->contact2_name ?? null,
                            $employee->familyDetails->contact3_name ?? null,
                        ])->filter()->isNotEmpty();

                        $noFamilyData = !$hasSpouse && !$hasChild && !$hasEmergency;
                    @endphp
                    <div class="tab-pane fade show active" id="family" role="tabpanel" aria-labelledby="family-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">FAMILY INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            @if($noFamilyData)
                                <div class="row mb-5 ml-1">
                                    <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                                        style="color: #ffffff; background-color: #00aeef;" data-toggle="modal"
                                        data-target="#addFamilyModal">
                                        <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add Family Details
                                    </a>
                                </div>
                            @endif
                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Spouse Information</p>
                            </div>
                            @if(empty($employee->familyDetails->spouse_ic))
                                <div class="ml-2 mb-3">No spouse</div>
                            @else
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Full Name:</label>
                                    <input type="text" value="{{ $employee->familyDetails->spouse_name ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Employment Status:</label>
                                    <input type="text" value="{{ $employee->familyDetails->spouse_status ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">MyKad:</label>
                                    <input type="text" value="{{ $employee->familyDetails->spouse_ic }}" class="input-field"
                                        readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Tax Number:</label>
                                    <input type="text" value="{{ $employee->familyDetails->spouse_tax ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                            @endif
                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Child Information</p>
                            </div>
                            @if(empty($employee->familyDetails->child1))
                                <div class="ml-2 mb-3">No children</div>
                            @else
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Number of Children (Below 18):</label>
                                    <input type="text" value="{{ $employee->familyDetails->noc_under ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Tax Relief (Below 18):</label>
                                    <input type="text" value="{{ $employee->familyDetails->tax_under ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Number of Children (Above 18 - Full Time
                                        Education):</label>
                                    <input type="text" value="{{ $employee->familyDetails->noc_above ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Tax Relief (Above 18):</label>
                                    <input type="text" value="{{ $employee->familyDetails->tax_above ?? 'N/A' }}"
                                        class="input-field" readonly>
                                </div>
                                @php
                                    // Get the family details of the employee
                                    $familyDetails = $employee->familyDetails;
                                    // Get the children fields
                                    $children = [
                                        'child1' => $familyDetails->child1 ?? null,
                                        'child2' => $familyDetails->child2 ?? null,
                                        'child3' => $familyDetails->child3 ?? null,
                                        'child4' => $familyDetails->child4 ?? null,
                                        'child5' => $familyDetails->child5 ?? null,
                                        'child6' => $familyDetails->child6 ?? null,
                                        'child7' => $familyDetails->child7 ?? null,
                                        'child8' => $familyDetails->child8 ?? null,
                                        'child9' => $familyDetails->child9 ?? null,
                                        'child10' => $familyDetails->child10 ?? null,
                                    ];
                                @endphp

                                @foreach ($children as $childLabel => $childValue)
                                    @if ($childValue)
                                        <div class="label-box-container">
                                            <label
                                                class="label-box text-sm font-bold">{{ ucfirst(str_replace('child', 'Child ', $childLabel)) }}:</label>
                                            <input type="text" value="{{ $childValue }}" class="input-field" readonly>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            @php
                                $contacts = [
                                    'contact1' => [
                                        'name' => $employee->familyDetails->contact1_name ?? null,
                                        'no' => $employee->familyDetails->contact1_no ?? null,
                                        'rel' => $employee->familyDetails->contact1_rel ?? null,
                                        'add' => $employee->familyDetails->contact1_add ?? null,
                                    ],
                                    'contact2' => [
                                        'name' => $employee->familyDetails->contact2_name ?? null,
                                        'no' => $employee->familyDetails->contact2_no ?? null,
                                        'rel' => $employee->familyDetails->contact2_rel ?? null,
                                        'add' => $employee->familyDetails->contact2_add ?? null,
                                    ],
                                    'contact3' => [
                                        'name' => $employee->familyDetails->contact3_name ?? null,
                                        'no' => $employee->familyDetails->contact3_no ?? null,
                                        'rel' => $employee->familyDetails->contact3_rel ?? null,
                                        'add' => $employee->familyDetails->contact3_add ?? null,
                                    ],
                                ];
                            @endphp

                            <div
                                style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Emergency Contact</p>
                            </div>

                            @if(empty($contacts) || collect($contacts)->filter(fn($c) => $c['name'])->isEmpty())
                                <div class="ml-2 mb-3">No emergency contact</div>
                            @else
                                @foreach($contacts as $index => $contact)
                                    @if(!empty($contact['name']))
                                        <div
                                            style="background-color: #9898e6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                            <p style="margin: 0; font-size: 14px;">Emergency Contact {{ substr($index, -1) }}</p>
                                        </div>
                                        <div class="label-box-container">
                                            <label class="label-box text-sm font-bold">Name:</label>
                                            <input type="text" value="{{ $contact['name'] }}" class="input-field" readonly>
                                        </div>
                                        <div class="label-box-container">
                                            <label class="label-box text-sm font-bold">Phone Number:</label>
                                            <input type="text" value="{{ $contact['no'] }}" class="input-field" readonly>
                                        </div>
                                        <div class="label-box-container">
                                            <label class="label-box text-sm font-bold">Relationship:</label>
                                            <input type="text" value="{{ $contact['rel'] }}" class="input-field" readonly>
                                        </div>
                                        <div class="label-box-container">
                                            <label class="label-box text-sm font-bold">Address:</label>
                                            <textarea class="input-field" readonly>{{ $contact['add'] }}</textarea>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->access, ['Admin', 'HR']))
                    <!-- Achievement info -->
                    <div class="tab-pane fade show active" id="achievement" role="tabpanel"
                        aria-labelledby="achievement-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">ACHIEVEMENT/PROFESSIONAL INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Qualification:</label>
                                <textarea class="input-field" readonly rows="3">{{ $employee->qualification }}</textarea>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Years of Experience:</label>
                                <input type="text" value="{{ $employee->experience }}" class="input-field" readonly>
                            </div>
                            <!-- Add empty placeholders to align with the larger tab -->
                            <div class="label-box-container invisible">
                                <label class="label-box">Placeholder:</label>
                                <input type="text" class="input-field" readonly>
                            </div>
                        </div>
                    </div>
                    @endif
            </form>
            @if(in_array(auth()->user()->access, ['Admin', 'HR']))
            <!-- Document Upload Tab -->
            <div class="tab-pane fade show active" id="document" role="tabpanel" aria-labelledby="document-tab">
                <div class="personal-information flex justify-between items-center" style="gap: 8px;">
                    <p class="text-lg font-bold m-0">DOCUMENTS</p>
                    <div style="display: flex; align-items: center; gap: 8px; flex-grow: 1; max-width: 400px;">
                        <input type="text" id="searchInput" placeholder="Search file..." class="search-box"
                            style="flex-grow: 1; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px;">
                        <select id="searchMode"
                            style="padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; background: white;">
                            <option value="" selected disabled hidden>Search By:</option>
                            <option value="fileName">File Name</option>
                            <option value="reference">Reference</option>
                        </select>
                    </div>
                </div>
                @if ($dbFiles->count() > 0)
                    <ul id="fileList" class="document-list">
                        @foreach ($dbFiles as $file)
                            @php
                                $folderName = substr($employee->ssn_num, -7) . '-' . preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));
                                $fileUrl = asset('emp/' . $folderName . '/' . $file->file_name);
                                $reference = $file->description ?? 'No reference provided';
                            @endphp
                            <li>
                                <div class="file-info">
                                    <a href="{{ $fileUrl }}" target="_blank" class="file-link">{{ $file->file_name }}</a>
                                    <span class="file-reference">({{ $reference }})</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No files uploaded.</p>
                @endif
                <div class="personal-information">
                    <p class="text-lg font-bold">DOCUMENTS UPLOAD</p>
                </div>
                <form action="{{ route('uploadFile') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <input type="hidden" name="ssn_num" value="{{ $employee->ssn_num }}">

                    <div class="form-group">
                        <label for="file" class="form-label">Upload File:</label>
                        <input type="file" name="file" id="file" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="reference" class="form-label">Reference:</label>
                        <input type="text" name="reference" id="reference" class="form-input" placeholder="Enter reference"
                            required>
                    </div>

                    <button type="submit" class="submit-button">Upload</button>
                </form>


                <!-- Validation Errors -->
                <div id="validationErrors" class="alert alert-danger mt-3" style="display: none;"></div>

                <!-- Success Message -->
                <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
            </div>
            @endif
            @if(in_array(auth()->user()->access, ['Admin', 'HR', 'Technical']))
            <div class="tab-pane fade show active" id="asset" role="tabpanel" aria-labelledby="asset-tab">
                <div class="personal-information">
                    <p class="text-lg font-bold">ASSET INFORMATION</p>
                </div>

                @if($assignedAssets->isEmpty())
                    <p class="text-sm text-gray-600">No assets assigned to this employee.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                            <thead class="label-box text-sm"">
                                <tr>
                                    <th>No</th>
                                    <th>Asset ID</th>
                                    <th>Asset Name</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>S/N No</th>
                                    <th>Status</th>
                                    <th>DOP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedAssets as $index => $assignment)
                                    <tr style="font-size: 14px;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $assignment->asset->asset_id ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->asset_name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->category->name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->model ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->sn_no ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->status ?? 'N/A' }}</td>
                                        <td>{{ $assignment->asset->dop ?? 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#unassignModal"
                                                data-assetid="{{ $assignment->asset->id }}"
                                                data-employeeid="{{ $assignment->employee_id }}">
                                                <i class="fas fa-trash-alt fa-sm" style="color: white;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="mt-3">
                    <form action="{{ route('employees.assignAsset') }}" method="POST" class="form-inline">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <label for="asset_db_id" class="mr-2">Assign Asset:</label>
                        <select name="asset_db_id" id="asset_db_id" class="form-control mr-2" required>
                            <option value="">-- Select Asset --</option>
                            @foreach($unassignedAssets as $asset)
                                <option value="{{ $asset->id }}">
                                    {{ $asset->asset_id }} - {{ $asset->asset_name }} ({{ $asset->model }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
            @endif
            <div class="col-span-2 grid grid-cols-2 gap-2">
                <div class="mt-5">
                    <a href="{{ $employee->termination_date ? route('past.employees') : route('employees') }}" class="btn"
                        style="color: #ffffff; background-color: #00aeef">
                        Back to Employee List
                    </a>
                    <a href="{{ route('employees.edit', [
                        'lastSixDigits' => substr($employee->ssn_num, -7),
                        'employmentStatus' => $employee->employment_status,
                        'firstName' => str_replace(' ', '_', $employee->first_name)
                    ]) }}"
                        class="btn ml-2" style="color: #00aeef; border: 1px solid #00aeef; font-weight: bold;">Edit</a>
                </div>
            </div>
        </ul>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addFamilyModal" tabindex="-1" role="dialog" aria-labelledby="addFamilyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Family Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="familyForm" action="{{ route('family.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="employee_ssn" value="{{ $employee->ssn_num }}">
                        <!-- Custom Modal Tab Content -->
                        <div id="familyModalContent" class="custom-tab-pane active">
                            <div class="form-group mt-2">
                                <div class="row">
                                    <!-- Spouse -->
                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Spouse Information</h5>
                                        <label class="fw-bold" for="has_spouse">Are you married?</label>
                                        <select class="form-control mb-3" id="has_spouse">
                                            <option value="no" selected>No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <div id="spouse_details" style="display: none;">
                                            <label class="fw-bold" for="spouse_name">Full Name</label>
                                            <input type="text" class="form-control mb-3" id="spouse_name"
                                                name="spouse_name">
                                            <label class="fw-bold" for="spouse_status">Employment Status</label>
                                            <input type="text" class="form-control mb-3" id="spouse_status"
                                                name="spouse_status">
                                            <label class="fw-bold" for="spouse_ic">MyKad</label>
                                            <input type="text" class="form-control mb-3" id="spouse_ic" name="spouse_ic">
                                            <label class="fw-bold" for="spouse_tax">Tax Number</label>
                                            <input type="text" class="form-control mb-3" id="spouse_tax" name="spouse_tax">
                                        </div>
                                    </div>

                                    <!-- Child -->
                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Child Information</h5>
                                        <label class="fw-bold" for="has_child">Do you have children?</label>
                                        <select class="form-control mb-3" id="has_child">
                                            <option value="no" selected>No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <div id="child_details" style="display: none;">
                                            <label class="fw-bold" for="noc_under">Number of Children (Below 18)</label>
                                            <input type="text" class="form-control mb-3" id="noc_under" name="noc_under"
                                                value="{{ $employee->familyDetails->noc_under ?? '' }}">
                                            <label class="fw-bold" for="tax_under">Tax Relief (Below 18)</label>
                                            <input type="text" class="form-control mb-3" id="tax_under" name="tax_under"
                                                value="{{ $employee->familyDetails->tax_under ?? '' }}">
                                            <label class="fw-bold" for="noc_above">Number of Children (Above 18 - Full Time
                                                Education)</label>
                                            <input type="text" class="form-control mb-3" id="noc_above" name="noc_above"
                                                value="{{ $employee->familyDetails->noc_above ?? '' }}">
                                            <label class="fw-bold" for="tax_above">Tax Relief (Above 18)</label>
                                            <input type="text" class="form-control mb-3" id="tax_above" name="tax_above"
                                                value="{{ $employee->familyDetails->tax_above ?? '' }}">
                                            <div id="child_list"></div>
                                            <a href="#" id="add_child" class="fw-bold text-primary d-block mt-2">+ Add
                                                Child</a>
                                        </div>
                                    </div>

                                    <!-- Emergency -->
                                    <div class="col-md-4">
                                        <h5 style="color:#000000">Emergency Contact</h5>
                                        <label class="fw-bold" for="has_emergency">Do you have emergency contact?</label>
                                        <select class="form-control mb-3" id="has_emergency">
                                            <option value="no" selected>No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <div id="emergency_details" style="display: none;">
                                            <div id="emergency_contact_list"></div>
                                            <a href="#" id="add_emergency_contact"
                                                class="fw-bold text-primary d-block mt-2">+ Add Emergency Contact</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for file preview -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewLabel">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="filePreviewFrame" src="" width="100%" height="600px" style="display: none;"></iframe>
                    <div id="unsupportedFile" style="display: none;">
                        <p>This file cannot be previewed. Please click the button below to download it.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="downloadFile" href="#" class="btn btn-primary" download>Download</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="unassignModal" tabindex="-1" role="dialog" aria-labelledby="unassignModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="unassignForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="asset_id" id="modalAssetId">
                <input type="hidden" name="employee_id" id="modalEmployeeId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove Assignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to unassign this asset from the employee?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Remove</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.nav-link');
            const tabPanes = document.querySelectorAll('.tab-pane');

            // ✅ Reset all tabs and panes, activate only the first one
            tabs.forEach((tab, index) => {
                if (index === 0) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });

            tabPanes.forEach((pane, index) => {
                if (index === 0) {
                    pane.classList.add('active');
                } else {
                    pane.classList.remove('active');
                }
            });

            // Handle tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active from all
                    tabs.forEach(tab => tab.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    // Add active to clicked
                    this.classList.add('active');
                    const targetPane = document.querySelector(this.getAttribute('href'));
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        });
    </script>
    <!-- Include jQuery and Bootstrap JS for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Handle file preview click
            $('.preview-file').on('click', function (e) {
                var fileUrl = $(this).attr('href'); // Get the file URL from the link
                var fileExt = fileUrl.split('.').pop().toLowerCase(); // Get the file extension
                var previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'txt'];

                if (previewableTypes.includes(fileExt)) {
                    // Prevent default navigation for previewable files
                    e.preventDefault();

                    // Preview directly for pdf, images, and txt
                    $('#filePreviewFrame').attr('src', fileUrl).show();
                    $('#unsupportedFile').hide();
                    $('#filePreviewModal').modal('show');
                } else {
                    // Allow default behavior for non-previewable files (e.g., downloading)
                    window.location.href = fileUrl;
                }
            });
        });


    </script>
    <!-- File search -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const searchMode = document.getElementById('searchMode');
            const listItems = document.querySelectorAll('.document-list li');

            searchInput.addEventListener('input', function () {
                const query = searchInput.value.toLowerCase();
                const mode = searchMode.value;

                listItems.forEach(item => {
                    const fileName = item.querySelector('.file-link').textContent.toLowerCase();
                    const reference = item.querySelector('.file-reference').textContent.toLowerCase();

                    let show = false;

                    if (mode === 'fileName') {
                        show = fileName.startsWith(query);
                    } else if (mode === 'reference') {
                        show = reference.includes(query); // or .startsWith(query) if you want stricter match
                    }

                    item.style.display = show ? '' : 'none';
                });
            });
        });
    </script>
    <script>
        document.getElementById("has_spouse").addEventListener("change", function () {
            var spouseDetails = document.getElementById("spouse_details");
            if (this.value === "yes") {
                spouseDetails.style.display = "block";
            } else {
                spouseDetails.style.display = "none";
            }
        });
        document.getElementById("has_child").addEventListener("change", function () {
            var childDetails = document.getElementById("child_details");
            if (this.value === "yes") {
                childDetails.style.display = "block";
            } else {
                childDetails.style.display = "none";
            }
        });
        document.getElementById("has_emergency").addEventListener("change", function () {
            var emergencyDetails = document.getElementById("emergency_details");
            if (this.value === "yes") {
                emergencyDetails.style.display = "block";
            } else {
                emergencyDetails.style.display = "none";
            }
        });
    </script>
    <script>
        document.getElementById("has_child").addEventListener("change", function () {
            let childDetails = document.getElementById("child_details");
            if (this.value === "yes") {
                childDetails.style.display = "block"; // Show child section
            } else {
                childDetails.style.display = "none"; // Hide child section
                document.getElementById("child_list").innerHTML = ""; // Clear dynamically added children
                childCount = 0; // Reset child count
                childIndex = 0; // Ensure numbering continues correctly
            }
        });

        let childCount = 0; // Total children ever added (keeps track of deleted ones)
        let childIndex = 0; // Current displayed count

        document.getElementById("add_child").addEventListener("click", function (event) {
            event.preventDefault(); // Prevent page refresh

            childCount++; // Track total children added
            childIndex++; // Track current numbering (ensures continuity after deletions)

            let childSection = document.createElement("div");
            childSection.classList.add("card", "mb-2");
            childSection.setAttribute("data-child-index", childIndex); // Track child ID for deletion

            childSection.innerHTML = `
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Child ${childIndex}</span>
                            <div>
                            <button class="btn btn-sm btn-light toggle-child" data-target="child${childIndex}">
                                <span class="arrow">▼</span>
                            </button>
                            <button class="btn btn-sm btn-danger delete-child">X</button>
                            </div>
                        </div>
                        <div id="child${childIndex}" class="collapse show">
                            <div class="card-body">
                            <label class="fw-bold">Full Name</label>
                            <input type="text" class="form-control mb-3" name="child${childIndex}" placeholder="Enter child's name">
                            </div>
                        </div>
                    `;

            document.getElementById("child_list").appendChild(childSection);
        });

        // Event Delegation for dynamically added elements
        document.getElementById("child_list").addEventListener("click", function (event) {
            let toggleBtn = event.target.closest(".toggle-child");
            if (toggleBtn) {
                event.preventDefault();
                let target = document.getElementById(toggleBtn.dataset.target);
                if (target) target.classList.toggle("show");
                return;
            }

            if (event.target.classList.contains("delete-child")) {
                event.preventDefault();
                let childCard = event.target.closest(".card");
                childCard.remove();

                // Renumber all remaining children
                const cards = document.querySelectorAll("#child_list .card");
                childIndex = 0; // Reset index

                cards.forEach((card, idx) => {
                    childIndex = idx + 1;
                    card.setAttribute("data-child-index", childIndex);

                    // Update card title
                    const title = card.querySelector(".fw-bold");
                    if (title) title.textContent = `Child ${childIndex}`;

                    // Update toggle button and its target
                    const toggleBtn = card.querySelector(".toggle-child");
                    const targetDiv = card.querySelector(".collapse");
                    if (toggleBtn && targetDiv) {
                        toggleBtn.setAttribute("data-target", `child${childIndex}`);
                        targetDiv.id = `child${childIndex}`;
                    }

                    // Update input name attribute
                    const input = card.querySelector("input[type='text']");
                    if (input) input.name = `child${childIndex}`;
                });
            }
        });
    </script>
    <script>
        let emergencyCount = 0;
        const maxEmergency = 3;

        document.getElementById("has_emergency").addEventListener("change", function () {
            let section = document.getElementById("emergency_section");
            if (this.value === "yes") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
                document.getElementById("emergency_contact_list").innerHTML = "";
                emergencyCount = 0;
            }
        });

        document.getElementById("add_emergency_contact").addEventListener("click", function (event) {
            event.preventDefault();

            if (emergencyCount >= maxEmergency) {
                alert("Maximum 3 emergency contacts allowed.");
                return;
            }

            emergencyCount++;
            let i = emergencyCount;

            const wrapper = document.createElement("div");
            wrapper.className = "card mb-2";
            wrapper.setAttribute("data-contact-index", i);

            wrapper.innerHTML = `
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Emergency Contact ${i}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-light toggle-contact" data-target="contact${i}"><span class="arrow">▼</span></button>
                                <button type="button" class="btn btn-sm btn-danger delete-contact">X</button>
                            </div>
                        </div>
                        <div id="contact${i}" class="collapse show">
                            <div class="card-body">
                                <label class="fw-bold" for="contact${i}_name">Name:</label>
                                <input type="text" name="contact${i}_name" class="form-control mb-3">

                                <label class="fw-bold" for="contact${i}_no">Phone Number:</label>
                                <input type="text" name="contact${i}_no" class="form-control mb-3">

                                <label class="fw-bold" for="contact${i}_rel">Relationship:</label>
                                <input type="text" name="contact${i}_rel" class="form-control mb-3">

                                <label class="fw-bold" for="contact${i}_add">Address:</label>
                                <textarea name="contact${i}_add" class="form-control mb-3"></textarea>
                            </div>
                        </div>
                    `;

            document.getElementById("emergency_contact_list").appendChild(wrapper);
        });

        document.getElementById("emergency_contact_list").addEventListener("click", function (event) {
            let toggleBtn = event.target.closest(".toggle-contact");
            if (toggleBtn) {
                event.preventDefault();
                let target = document.getElementById(toggleBtn.dataset.target);
                if (target) target.classList.toggle("show");
                return;
            }

            if (event.target.classList.contains("delete-contact")) {
                event.preventDefault();
                let card = event.target.closest(".card");
                card.remove();

                // Renumber all remaining emergency contacts
                const cards = document.querySelectorAll("#emergency_contact_list .card");
                emergencyCount = 0; // Reset counter

                cards.forEach((card, idx) => {
                    emergencyCount = idx + 1;
                    card.setAttribute("data-contact-index", emergencyCount);

                    // Update card header text
                    const title = card.querySelector(".fw-bold");
                    if (title) title.textContent = `Emergency Contact ${emergencyCount}`;

                    // Update toggle button and its target
                    const toggleBtn = card.querySelector(".toggle-contact");
                    const targetDiv = card.querySelector(".collapse");
                    if (toggleBtn && targetDiv) {
                        toggleBtn.setAttribute("data-target", `contact${emergencyCount}`);
                        targetDiv.id = `contact${emergencyCount}`;
                    }

                    // Update input/textarea names
                    const name = card.querySelector(`input[name^="contact"][name$="_name"]`);
                    const phone = card.querySelector(`input[name^="contact"][name$="_no"]`);
                    const rel = card.querySelector(`input[name^="contact"][name$="_rel"]`);
                    const addr = card.querySelector(`textarea[name^="contact"][name$="_add"]`);

                    if (name) name.name = `contact${emergencyCount}_name`;
                    if (phone) phone.name = `contact${emergencyCount}_no`;
                    if (rel) rel.name = `contact${emergencyCount}_rel`;
                    if (addr) addr.name = `contact${emergencyCount}_add`;
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('familyModal'); // modal ID
            const form = document.getElementById('familyForm');

            modal.addEventListener('hidden.bs.modal', function () {
                form.reset(); // This resets the form when modal is closed
            });
        });
    </script>
<script>
    $('#unassignModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var assetId = button.data('assetid');
        var employeeId = button.data('employeeid');

        $('#modalAssetId').val(assetId);
        $('#modalEmployeeId').val(employeeId);

        $('#unassignForm').attr('action', '/employees/unassign-asset');
    });
</script>
<script>
    $(document).ready(function () {
        const hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }

        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            const newHash = $(e.target).attr('href');
            history.replaceState(null, null, newHash);
        });
    });
</script>
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.style.display = 'none';
        }, 3000); // hides after 4 seconds
    </script>


@endsection