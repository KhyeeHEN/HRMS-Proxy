@extends('layout')

@section('title', 'Family')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Family</h1>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
                style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addFamilyModal">
                <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Family
            </a>
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
                        <form id="familyForm" action="{{ route('family.store') }}" method="POST">
                            @csrf
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="family-tab" data-toggle="tab" href="#family" role="tab"
                                        aria-controls="family" aria-selected="true">Family Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="employee-tab" data-toggle="tab" href="#employee" role="tab"
                                        aria-controls="employee" aria-selected="false">Employee</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="familyTabContent">
                                <!-- Family Tab Content -->
                                <div class="tab-pane fade show active" id="family" role="tabpanel"
                                    aria-labelledby="family-tab">
                                    <!-- Spouse Information -->
                                    <div class="form-group mt-4">
                                        <div class="row">
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
                                                    <input type="text" class="form-control mb-3" id="spouse_ic"
                                                        name="spouse_ic">

                                                    <label class="fw-bold" for="spouse_tax">Tax Number</label>
                                                    <input type="text" class="form-control mb-3" id="spouse_tax"
                                                        name="spouse_tax">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 style="color:#000000">Child Information</h5>
                                                <label class="fw-bold" for="has_child">Do you have children?</label>
                                                <select class="form-control mb-3" id="has_child">
                                                    <option value="no" selected>No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                                <div id="child_details" style="display: none;">
                                                    <label class="fw-bold" for="noc_under">Number of Children (Below
                                                        18)</label>
                                                    <input type="text" class="form-control mb-3" id="noc_under"
                                                        name="noc_under"
                                                        value="{{ $employee->familyDetails->noc_under ?? '' }}">

                                                    <label class="fw-bold" for="tax_under">Tax Relief (Below 18)</label>
                                                    <input type="text" class="form-control mb-3" id="tax_under"
                                                        name="tax_under"
                                                        value="{{ $employee->familyDetails->tax_under ?? '' }}">

                                                    <label class="fw-bold" for="noc_above">Number of Children (Above 18 -
                                                        Full Time Education)</label>
                                                    <input type="text" class="form-control mb-3" id="noc_above"
                                                        name="noc_above"
                                                        value="{{ $employee->familyDetails->noc_above ?? '' }}">

                                                    <label class="fw-bold" for="tax_above">Tax Relief (Above 18)</label>
                                                    <input type="text" class="form-control mb-3" id="tax_above"
                                                        name="tax_above"
                                                        value="{{ $employee->familyDetails->tax_above ?? '' }}">

                                                    <div id="child_list"></div>
                                                    <a href="#" id="add_child" class="fw-bold text-primary d-block mt-2">+
                                                        Add Child</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 style="color:#000000">Emergency Contact</h5>
                                                <label class="fw-bold" for="has_emergency">Do you have emergency
                                                    contact?</label>
                                                <select class="form-control mb-3" id="has_emergency">
                                                    <option value="no" selected>No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                                <div id="emergency_details" style="display: none;">
                                                    <div id="emergency_contact_list"></div>
                                                    <a href="#" id="add_emergency_contact"
                                                        class="fw-bold text-primary d-block mt-2">+ Add Emergency
                                                        Contact</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employee Tab Content -->
                                <div class="tab-pane fade" id="employee" role="tabpanel" aria-labelledby="employee-tab">
                                    <div class="form-group mt-4">
                                        <h5 style="color:#000000">Employee</h5>
                                        <label for="employee_name" class="fw-bold">Select which employee does it belongs
                                            to:</label>
                                        <select class="form-control mb-3" id="employee_name" name="employee_name">
                                            <option value="">-- Select Employee --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->first_name }}</option>
                                            @endforeach
                                        </select>
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
    </div>
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
@endsection