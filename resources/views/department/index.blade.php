@extends('layout')

@section('title', 'Department')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        @if ($errors->any())
            <script>
                $(document).ready(function () {
                    $('#addDepartmentModal').modal('show');
                });
            </script>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Department</h1>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Add Button -->
                    <a href="#" class="btn btn-sm shadow-sm" style="color: #ffffff; background-color: #00aeef;"
                        data-toggle="modal" data-target="#addDepartmentModal">
                        <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Department
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Department</th>
                                    <th>Total Employees</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr style="font-size: 14px;">
                                        <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $department->name }}</td>
                                        <td>{{ $department->employees_count }}</td>
                                        <td>
                                            <a href="{{ route('department.employees', $department->id) }}"
                                                class="btn btn-sm btn-primary mb-1">
                                                View Employees
                                            </a>
                                            <a href="{{ route('department.edit', $department->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('department.destroy', $department->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this department?');">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($departments->isEmpty())
                                    <tr>
                                        <td colspan="3">No department found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addDepartmentForm" method="POST" action="{{ route('department.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Department</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Department Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">Add
                            Department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pagination JS -->
    <script>
        document.getElementById('pagination').addEventListener('change', function () {
            const perPage = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    </script>

@endsection