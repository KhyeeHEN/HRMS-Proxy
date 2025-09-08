@extends('layout')

@section('title', 'Users')

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
                    $('#addUserModal').modal('show'); // Keep modal open if validation fails
                });
            </script>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users</h1>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Add User Button -->
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm"
                        style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-user-plus fa-sm mr-1" style="color: white;"></i> Add New User
                    </a>

                    <!-- Pagination Selection -->
                    <div class="d-flex align-items-center">
                        <label for="pagination" class="mr-2 mb-0">Show</label>
                        <select id="pagination" class="form-control d-inline-block w-auto">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ml-2">entries</span>
                    </div>
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
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Access Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr style="font-size: 14px;">
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->access }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="7">No users found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4 mb-3">
                            {{ $users->appends(['per_page' => request('per_page', 10)])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control"
                                value="{{ old('username') }}" required>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                                required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="access">Access Level:</label>
                            <select id="access" name="access" class="form-control" required>
                                <option value="">Select Access Level</option>
                                <option value="Admin" {{ old('access') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="HR" {{ old('access') == 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="Technical" {{ old('access') == 'Technical' ? 'selected' : '' }}>Technical</option>
                            </select>
                            @error('access')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">Add
                            User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (For Modal Functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- pagination -->
    <script>
        document.getElementById('pagination').addEventListener('change', function () {
            let perPage = this.value;
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('per_page', perPage);
            window.location.href = currentUrl.toString();
        });
    </script>
    <script>
        document.getElementById('pagination').addEventListener('change', function () {
            const perPage = this.value;
            const url = new URL(window.location.href);

            // Set 'per_page' in URL
            url.searchParams.set('per_page', perPage);

            // Reset to first page
            url.searchParams.set('page', 1);

            // Redirect
            window.location.href = url.toString();
        });
    </script>


    <!-- validation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("#addUserForm input, #addUserForm select").forEach(field => {
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

                fetch("{{ route('users.validateField') }}", {
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