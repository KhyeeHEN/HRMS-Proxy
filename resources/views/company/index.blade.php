@extends('layout')

@section('title', 'Company')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="{{ asset('css/search.css') }}" rel="stylesheet">

<style>
    .form-input {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 0.9rem;
    }
</style>

@section('content')

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <script>
                $(document).ready(function () {
                    $('#addCompanyModal').modal('show'); // Keep modal open if validation fails
                });
            </script>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Subsidiaries</h1>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Add Company Button -->
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm"
                        style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addCompanyModal">
                        <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Subsidiary
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Aliases</th>
                                    <th>Address</th>
                                    <th>Total Employees</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                                    <tr style="font-size: 14px;">
                                        <td>{{ ($companies->currentPage() - 1) * $companies->perPage() + $loop->iteration }}</td>
                                        <td>{{ $company->title }}</td>
                                        <td>{{ $company->description }}</td>
                                        <td>{{ $company->address }}</td>
                                        <td>{{ $company->employees_count }}</td>
                                        <td>
                                            <a href="{{ route('company.employees', $company->id) }}" class="btn btn-sm btn-primary mb-1">View Employees</a>
                                            <a href="{{ route('company.edit', $company->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($companies->isEmpty())
                                    <tr>
                                        <td colspan="7">No company found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4 mb-3">
                            {{ $companies->appends(['per_page' => request('per_page', 10)])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Company Modal -->
    <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyModalLabel">Add Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCompanyForm" method="POST" action="{{ route('company.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Aliases:</label>
                            <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}"
                                required>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea id="address" name="address" class="form-control" required>{{ old('address') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">Add
                            Subsidiary</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (For Modal Functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- validation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("#addCompanyForm input, #addCompanyForm select").forEach(field => {
                field.addEventListener("blur", function () {
                    validateField(this);
                });
            });

            function validateField(field) {
                let fieldName = field.name;
                let fieldValue = field.value;
                let errorContainer = field.closest(".form-group").querySelector("small.text-danger");

                if (!errorContainer) {
                    errorContainer = document.createElement("small");
                    errorContainer.classList.add("text-danger");
                    field.closest(".form-group").appendChild(errorContainer);
                }

                fetch("{{ route('company.validateField') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({ field: fieldName, [fieldName]: fieldValue })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            errorContainer.textContent = data.error;
                            field.classList.add("is-invalid");
                        } else {
                            errorContainer.textContent = "";
                            field.classList.remove("is-invalid");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        });
    </script>

@endsection
