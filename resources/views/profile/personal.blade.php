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
        background-color: #8EACCD;
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

        .col-span-2.grid.grid-cols-2.gap-2 {
            flex-direction: column;
            /* Stack all label/input pairs in a single column */
        }
    }

    .profile-image img {
        width: 150px;
        height: 160px;
        float: right;
        margin-right: 2px;
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
        display: none;
    }

    /* Show the active section */
    .tab-pane.active {
        display: block;
    }
</style>

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Profile</h1>
    </div>

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
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
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
            </ul></br>
            <!-- Tab content -->
            <div class="tab-content" id="profileContent">
                <!-- Personal Info -->
                <div class="tab-pane active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <div class="personal-information">
                        <p class="text-lg font-bold">PERSONAL INFORMATION</p>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-2">
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">First Name:</label>
                            <input type="text" value="{{ $employee->first_name }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Last Name:</label>
                            <input type="text" value="{{ $employee->last_name }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">MyKad Number:</label>
                            <input type="text" value="{{ $employee->ssn_num }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Nationality:</label>
                            <input type="text" value="{{ $employee->national->name ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Birthday:</label>
                            <input type="text"
                                value="{{ $employee->birthday ? $employee->birthday->format('d-m-Y') : '' }}"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Gender:</label>
                            <input type="text" value="{{ $employee->gender }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Age:</label>
                            <input type="text"
                                value="@if($employee->birthday){{ \Carbon\Carbon::now()->year - \Carbon\Carbon::parse($employee->birthday)->year }}@else N/A @endif"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Ethnicity:</label>
                            <input type="text" value="{{ $employee->ethnicityName->name ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Marital Status:</label>
                            <input type="text" value="{{ $employee->marital_status }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Dependents:</label>
                            <input type="text" value="{{ $totalDependents }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Address 1:</label>
                            <input type="text" value="{{ $employee->address1 }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Address 2:</label>
                            <input type="text" value="{{ $employee->address2 }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Postal Code:</label>
                            <input type="text" value="{{ $employee->postal_code }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">City:</label>
                            <input type="text" value="{{ $employee->city }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">State:</label>
                            <input type="text" value="{{ $employee->stateName->name ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Country:</label>
                            <input type="text" value="{{ $employee->countryName->name ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Home Phone:</label>
                            <input type="text" value="{{ $employee->home_phone }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Mobile Phone:</label>
                            <input type="text" value="{{ $employee->mobile_phone }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Private Email:</label>
                            <input type="text" value="{{ $employee->private_email }}" class="input-field" readonly>
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
                <!-- Work Info -->
                <div class="tab-pane" id="work" role="tabpanel" aria-labelledby="work-tab">
                    <div class="personal-information">
                        <p class="text-lg font-bold">WORK INFORMATION</p>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-2">
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Employee ID:</label>
                            <input type="text" value="{{ $employee->employee_id }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Title:</label>
                            <input type="text" value="{{ $employee->job_title }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Company:</label>
                            <input type="text" value="{{ $employee->companyStructure->title ?? ' ' }}"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Department:</label>
                            <input type="text" value="{{ $employee->department }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Joined Date:</label>
                            <input type="text"
                                value="{{ $employee->joined_date ? $employee->joined_date->format('d-m-Y') : ''}}"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Reporting Manager:</label>
                            <input type="text" value="{{ $employee->supervisor ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Employment Status:</label>
                            <input type="text" value="{{ $employee->employmentStatus->name ?? 'N/A' }}"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Supervisor/Team Leader:</label>
                            <input type="text" value="{{ $employee->indirect_supervisors ?? 'N/A' }}"
                                class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Work Email:</label>
                            <input type="text" value="{{ $employee->work_email }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Work Phone:</label>
                            <input type="text" value="{{ $employee->work_phone }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Pay Grade:</label>
                            <input type="text" value="{{ $employee->payGrade->name ?? 'N/A' }}" class="input-field"
                                readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Status:</label>
                            <input type="text" value="{{ $employee->status }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Branch:</label>
                            <input type="text" value="{{ $employee->branch }}" class="input-field" readonly>
                        </div>

                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Work Station:</label>
                            <input type="text" value="{{ $employee->work_station_id }}" class="input-field" readonly>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Resigned Date:</label>
                            <input type="text"
                                value="{{ $employee->termination_date ? $employee->termination_date->format('d-m-Y') : 'N/A' }}"
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

                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Notes:</label>
                            <input type="text" value="{{ $employee->notes }}" class="input-field" readonly>
                        </div>
                    </div>
                </div>
                <!-- Family info -->
                <div class="tab-pane" id="family" role="tabpanel" aria-labelledby="family-tab">
                    <div class="personal-information">
                        <p class="text-lg font-bold">FAMILY INFORMATION</p>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-2">
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

                        @foreach($contacts as $index => $contact)
                            @if($contact['name'])
                                <div
                                    style="background-color: #ACB1D6; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #fff; width: 100%;">
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
                    </div>
                </div>
                <!-- Achievement info -->
                <div class="tab-pane" id="achievement" role="tabpanel" aria-labelledby="achievement-tab"> 
                    <div class="personal-information">
                        <p class="text-lg font-bold">ACHIEVEMENT/PROFESSIONAL INFORMATION</p>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-2">
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Qualification:</label>
                            <textarea class="input-field" readonly>{{ $employee->qualification }}</textarea>
                        </div>
                        <div class="label-box-container">
                            <label class="label-box text-sm font-bold">Years of Experience:</label>
                            <input type="text" value="{{ $employee->experience }}" class="input-field" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-2 grid grid-cols-2 gap-2">
                <div class="mt-5">
                    <a href="{{ $employee->termination_date ? route('past.employees') : route('employees') }}"
                        class="btn" style="color: #ffffff; background-color: #00aeef">
                        Back to Employee List
                    </a>
                    <a href="{{ route('employees.edit', ['lastSixDigits' => substr($employee->ssn_num, -7), 'employmentStatus' => $employee->employment_status]) }}"
                        class="btn ml-2" style="color: #00aeef; border: 1px solid #00aeef; font-weight: bold;">Edit</a>
                </div>
            </div>
        </form>
    </ul>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle tab switching
        const tabs = document.querySelectorAll('.nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active class from all tabs and panes
                tabs.forEach(tab => tab.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Add active class to the clicked tab and corresponding pane
                this.classList.add('active');
                const targetPane = document.querySelector(this.getAttribute('href'));
                targetPane.classList.add('active');
            });
        });
    });
</script>
@endsection