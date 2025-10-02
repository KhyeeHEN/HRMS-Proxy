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
        background: linear-gradient(to right, #003cb3, #6699ff);
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
        background-color: #e0ccff;
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
        margin-top: 4px;
    }

    .logo-container img {
        float: left;
    }
    .profile-image {
        position: relative;
        cursor: pointer;
    }

    .profile-image input[type="file"] {
        display: none;
    }

    .profile-image:hover .overlay {
        display: flex;
    }

    .profile-image .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        display: none;
        align-items: center;
        justify-content: center;
        text-align: center;
        z-index: 1;
    }

    .container {
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

            <form method="POST"
            action="{{ route('employees.update', [
                    'lastSixDigits' => substr($employee->ssn_num, -7),
                    'employmentStatus' => $employee->employment_status,
                    'firstName' => str_replace(' ', '_', $employee->first_name)
                ]) }}"
                class="grid gap-4 p-3 rounded shadow-md" style="background-color: #ffffff;" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-span-2 text-left">
                    <div class="profile-container">
                        <!-- Editable Employee Photo Section -->
                        <div class="profile-image"
                            style="float: left; border-radius: 50%; width: 150px; height: 150px; overflow: hidden; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; margin: 20px">
                            <input type="file" id="photoInput" name="photo" accept="image/*">
                            <label for="photoInput" style="width: 100%; height: 100%; display: block;">
                                <div class="overlay">Change Photo</div>
                                @if($employee->photo)
                                    <img id="photoPreview" src="data:image/jpeg;base64,{{ base64_encode($employee->photo) }}" alt="Employee Photo"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img id="photoPreview" src="" alt="Default Preview"
                                        style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray" width="100%" height="100%" id="defaultIcon">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 
                                            0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                @endif
                            </label>
                            <!-- File name display -->
                            <div id="fileNameDisplay" style="text-align: center; margin-top: 10px; font-size: 0.9rem; color: #333;"></div>
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
                </ul></br>
                <!-- Tab content -->
                <div class="tab-content" id="profileContent">
                    <!-- Personal Info -->
                    <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">PERSONAL INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Personal Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">First Name:</label>
                                <input type="text" name="first_name" value="{{ $employee->first_name }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Birthday:</label>
                                <input type="text" name="birthday"
                                    value="{{ $employee->birthday ? $employee->birthday->format('d-m-Y') : '' }}"
                                    class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Last Name:</label>
                                <input type="text" name="last_name" value="{{ $employee->last_name }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Nationality:</label>
                                <select name="nationality" id="nationality" class="input-field">
                                    @foreach($nationals as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->nationality == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">MyKad Number:</label>
                                <input type="text" name="ssn_num" value="{{ $employee->ssn_num }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Ethnicity:</label>
                                <select name="ethnicity" id="ethnicity" class="input-field">
                                    @foreach($ethnicities as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->ethnicity == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Gender:</label>
                                <input type="text" name="gender" value="{{ $employee->gender }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Age:</label>
                                <input type="text"
                                    value="@if($employee->birthday){{ \Carbon\Carbon::now()->year - \Carbon\Carbon::parse($employee->birthday)->year }}@else N/A @endif"
                                    class="input-field" readonly>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Marital Status:</label>
                                <input type="text" name="marital_status" value="{{ $employee->marital_status }}"
                                    class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Dependents:</label>
                                <input type="text" value="{{ $totalDependents }}" class="input-field" readonly>
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Contact Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Home Phone:</label>
                                <input type="text" name="home_phone" value="{{ $employee->home_phone }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Mobile Phone:</label>
                                <input type="text" name="mobile_phone" value="{{ $employee->mobile_phone }}"
                                    class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Private Email:</label>
                                <input type="text" name="private_email" value="{{ $employee->private_email }}"
                                    class="input-field">
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Address Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Address 1:</label>
                                <input type="text" name="address1" value="{{ $employee->address1 }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">City:</label>
                                <input type="text" name="city" value="{{ $employee->city }}" class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Address 2:</label>
                                <input type="text" name="address2" value="{{ $employee->address2 }}" class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold" for="state">State:</label>
                                <select name="state" id="state" class="input-field">
                                    @foreach($states as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->state == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Postal Code:</label>
                                <input type="text" name="postal_code" value="{{ $employee->postal_code }}"
                                    class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Country:</label>
                                <select name="country" id="country" class="input-field">
                                    @foreach($countries as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->country == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Employee Contributions</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">EPF Number:</label>
                                <input type="text" name="epf_no" value="{{ $employee->epf_no }}" class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">SOCSO Number:</label>
                                <input type="text" name="socso" value="{{ $employee->socso }}" class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">LHDN Number:</label>
                                <input type="text" name="lhdn_no" value="{{ $employee->lhdn_no }}" class="input-field">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">WORK INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Job Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Employee ID:</label>
                                <input type="text" name="employee_id" value="{{ $employee->employee_id }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Title:</label>
                                <input type="text" name="job_title" value="{{ $employee->job_title }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold" for="company">Subsidiary:</label>
                                <select name="company" id="company" class="input-field">
                                    @foreach($companies as $id => $title)
                                        <option value="{{ $id }}" {{ $employee->company == $id ? 'selected' : '' }}>
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Department:</label>
                                <input type="text" name="department" value="{{ $employee->department }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Employment Status:</label>
                                <select name="employment_status" id="employment_status" class="input-field">
                                    @foreach($statuses as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->employment_status == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Joined Date:</label>
                                <input type="text" name="joined_date"
                                    value="{{ $employee->joined_date ? $employee->joined_date->format('d-m-Y') : ''}}"
                                    class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Status:</label>
                                <select name="status" class="input-field">
                                    <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="terminated" {{ $employee->status == 'terminated' ? 'selected' : '' }}>
                                        Terminated
                                    </option>
                                </select>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Resigned Date:</label>
                                <input type="text" name="termination_date"
                                    value="{{ $employee->termination_date ? $employee->termination_date->format('d-m-Y') : 'N/A' }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Pay Grade:</label>
                                <input type="text" name="pay_grade" value="{{ $employee->payGrade->name ?? 'N/A' }}"
                                    class="input-field" required>
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Reporting Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Reporting Manager:</label>
                                <input type="text" name="supervisor" value="{{ $employee->supervisor ?? 'N/A' }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Supervisor/Team Leader:</label>
                                <input type="text" name="indirect_supervisors"
                                    value="{{ $employee->indirect_supervisors ?? 'N/A' }}" class="input-field" required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Approver 1:</label>
                                <input type="text" name="approver1" value="{{ $employee->approver1 }}" class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Approver 2:</label>
                                <input type="text" name="approver2" value="{{ $employee->approver2 }}" class="input-field">
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Contact Information</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Email:</label>
                                <input type="text" name="work_email" value="{{ $employee->work_email }}" class="input-field"
                                    required>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Phone:</label>
                                <input type="text" name="work_phone" value="{{ $employee->work_phone }}"
                                    class="input-field">
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Work Location</p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Branch:</label>
                                <input type="text" name="branch" value="{{ $employee->branch }}" class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Work Station:</label>
                                <input type="text" name="work_station_id" value="{{ $employee->work_station_id }}"
                                    class="input-field">
                            </div>

                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Additional/p>
                            </div>

                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Notes:</label>
                                <input type="text" name="notes" value="{{ $employee->notes }}" class="input-field">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">FAMILY INFORMATION</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div
                                style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                <p style="margin: 0; font-size: 14px;">Spouse Information</p>
                            </div>
                            @if(empty($employee->familyDetails->spouse_ic))
                                <div class="ml-2 mb-3">No spouse</div>
                            @else
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Full Name:</label>
                                    <input type="text" name="spouse_name"
                                        value="{{ $employee->familyDetails->spouse_name ?? 'N/A' }}" class="input-field">
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Employment Status:</label>
                                    <input type="text" name="spouse_status"
                                        value="{{ $employee->familyDetails->spouse_status ?? 'N/A' }}" class="input-field">
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">MyKad:</label>
                                    <input type="text" name="spouse_ic" value="{{ $employee->familyDetails->spouse_ic }}"
                                        class="input-field">
                                </div>
                                <div class="label-box-container">
                                    <label class="label-box text-sm font-bold">Tax Number:</label>
                                    <input type="text" name="spouse_tax"
                                        value="{{ $employee->familyDetails->spouse_tax ?? 'N/A' }}" class="input-field">
                                </div>
                            @endif
                        </div>
                        <div
                            style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                            <p style="margin: 0; font-size: 14px;">Child Information</p>
                        </div>
                        @if(empty($employee->familyDetails->child1))
                            <div class="ml-2 mb-3">No children</div>
                        @else
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Number of Children (Below 18):</label>
                                <input type="text" name="noc_under" value="{{ $employee->familyDetails->noc_under ?? 'N/A' }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Tax Relief (Below 18):</label>
                                <input type="text" name="tax_under" value="{{ $employee->familyDetails->tax_under ?? 'N/A' }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Number of Children (Above 18 - Full Time
                                    Education):</label>
                                <input type="text" name="noc_above" value="{{ $employee->familyDetails->noc_above ?? 'N/A' }}"
                                    class="input-field">
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Tax Relief (Above 18):</label>
                                <input type="text" name="tax_above" value="{{ $employee->familyDetails->tax_above ?? 'N/A' }}"
                                    class="input-field">
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
                                        <input type="text" name="{{ $childLabel }}" value="{{ $childValue }}" class="input-field">
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
                            style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                            <p style="margin: 0; font-size: 14px;">Emergency Contact</p>
                        </div>

                        @if(empty($contacts) || collect($contacts)->filter(fn($c) => $c['name'])->isEmpty())
                            <div class="ml-2 mb-3">No emergency contact</div>
                        @else
                            @foreach($contacts as $index => $contact)
                                @if(!empty($contact['name']))
                                    <div
                                        style="background-color: #8533ff; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
                                        <p style="margin: 0; font-size: 14px;">Emergency Contact {{ substr($index, -1) }}</p>
                                    </div>
                                    <div class="label-box-container">
                                        <label class="label-box text-sm font-bold">Name:</label>
                                        <input type="text" name="{{ $index }}_name" value="{{ $contact['name'] }}" class="input-field">
                                    </div>
                                    <div class="label-box-container">
                                        <label class="label-box text-sm font-bold">Phone Number:</label>
                                        <input type="text" name="{{ $index }}_no" value="{{ $contact['no'] }}" class="input-field">
                                    </div>
                                    <div class="label-box-container">
                                        <label class="label-box text-sm font-bold">Relationship:</label>
                                        <input type="text" name="{{ $index }}_rel" value="{{ $contact['rel'] }}" class="input-field">
                                    </div>
                                    <div class="label-box-container">
                                        <label class="label-box text-sm font-bold">Address:</label>
                                        <textarea class="input-field" name="{{ $index }}_add">{{ $contact['add'] }}</textarea>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="tab-pane fade" id="achievement" role="tabpanel" aria-labelledby="achievement-tab">
                        <div class="personal-information">
                            <p class="text-lg font-bold">ACADEMIC/PROFESSIONAL ACHIEVEMENTS</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Qualification:</label>
                                <textarea class="input-field" name="qualification">{{ $employee->qualification }}</textarea>
                            </div>
                            <div class="label-box-container">
                                <label class="label-box text-sm font-bold">Years of Experience:</label>
                                <input type="text" name="experience" value="{{ $employee->experience }}"
                                    class="input-field">
                            </div>
                        </div>
                    </div>
            </form>
            <!-- Document Upload Tab -->
            <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                <div class="personal-information">
                    <p class="text-lg font-bold">DOCUMENTS</p>
                </div>
                @if ($dbFiles->count() > 0)
                    <div class="col-span-2 grid grid-cols-2 gap-2">
                        @foreach ($dbFiles as $file)
                            @php
                                // Construct the folder name
                                $folderName = substr($employee->ssn_num, -7) . '-' . preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));

                                // Create the file URL using the correct folder structure
                                $fileUrl = asset('emp/' . $folderName . '/' . $file->file_name);

                                // Get the reference description (if available)
                                $reference = $file->description ?? 'No reference provided';

                                // The file name for display (from the database)
                                $fileName = $file->file_name;
                            @endphp
                            <div class="label-box-container full-width">
                                <div class="label-box full-width">
                                    <label>
                                        <!-- The clickable link will use the file_name from the database -->
                                        <a href="{{ $fileUrl }}" target="_blank" class="preview-file">{{ $fileName }}</a>
                                        <span style="margin-left: 20px; color: #555;">Reference: {{ $reference }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
            <div class="col-span-2 grid grid-cols-2 gap-2">
                <div class="mt-5">
                    <a href="{{ route('employees') }}" class="btn back-to-employee-list"
                        style="color: #ffffff; background-color: #00aeef">Back to Employee List</a>
                        <a href="{{ route('employees.show', [
                            'lastSixDigits' => substr($employee->ssn_num, -7),
                            'employmentStatus' => $employee->employment_status,
                            'firstName' => str_replace(' ', '_', $employee->first_name)
                        ]) }}" class="btn"
                        style="color: #ffffff; background-color: #f44336;">Cancel</a>
                    <button type="submit" class="btn ml-2"
                        style="color: #00aeef; border: 1px solid #00aeef; font-weight: bold;">Save
                        Changes</button>
                </div>
            </div>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.nav-link');
            const tabPanes = document.querySelectorAll('.tab-pane');

            // Set only the first tab and pane to active + show
            tabs.forEach((tab, index) => {
                tab.classList.toggle('active', index === 0);
            });

            tabPanes.forEach((pane, index) => {
                pane.classList.toggle('active', index === 0);
                pane.classList.toggle('show', index === 0); // ✅ Important
            });

            // Handle tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active + show from all
                    tabs.forEach(tab => tab.classList.remove('active'));
                    tabPanes.forEach(pane => {
                        pane.classList.remove('active', 'show');
                    });

                    // Add active to clicked tab
                    this.classList.add('active');

                    // Show the associated tab-pane
                    const targetPane = document.querySelector(this.getAttribute('href'));
                    if (targetPane) {
                        targetPane.classList.add('active', 'show'); // ✅ Must have both
                    }
                });
            });
        });
    </script>
    <!-- Include jQuery and Bootstrap JS for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('photoInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('photoPreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    const svg = document.getElementById('defaultIcon');
                    if (svg) svg.style.display = 'none'; // hide SVG if shown before
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
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
    <script>
        let formChanged = false;

        // Track form changes
        document.querySelectorAll('input, select, textarea').forEach((input) => {
            input.addEventListener('change', () => {
                formChanged = true;
            });
        });

        // Handle navigation with unsaved changes
        document.querySelector('.back-to-employee-list').addEventListener('click', function (e) {
            if (formChanged) {
                e.preventDefault();
                if (confirm('You haven\'t saved your changes yet. Are you sure you want to leave?')) {
                    window.location.href = this.href;
                }
            }
        });
    </script>
@endsection

<!-- 