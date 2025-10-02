@extends('layout')

@section('title', 'Edit User')

@section('content')

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form id="editUserForm" method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Full Name:</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">New Password (Leave blank to keep current):</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="access">Access Level:</label>
                                <select id="access" name="access" class="form-control @error('access') is-invalid @enderror"
                                    required>
                                    <option value="Admin" {{ old('access', $user->access) == 'Admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="HR" {{ old('access', $user->access) == 'HR' ? 'selected' : '' }}>HR
                                    </option>
                                    <option value="HR" {{ old('access', $user->access) == 'Technical' ? 'selected' : '' }}>Technical
                                    </option>
                                </select>
                                @error('access')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">Update
                                User</button>
                            <a href="{{ route('users.index') }}" class="btn mt-4"
                                style="color: #ffffff; background-color: #bfbfbf;">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- validation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('#editUserForm input, #editUserForm select').forEach(field => {
                field.addEventListener('input', function () {
                    validateEditField(field);
                });
            });

            function validateEditField(field) {
                let fieldName = field.name;
                let fieldValue = field.value;
                let userId = "{{ $user->id }}"; // Pass user ID for uniqueness validation

                fetch("{{ route('users.validateEditField') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        field: fieldName,
                        [fieldName]: fieldValue,
                        user_id: userId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        let errorSpan = field.nextElementSibling;
                        if (errorSpan && errorSpan.classList.contains('text-danger')) {
                            errorSpan.remove();
                        }

                        if (data.error) {
                            let errorMessage = document.createElement('span');
                            errorMessage.classList.add('text-danger');
                            errorMessage.innerText = data.error;
                            field.classList.add('is-invalid');
                            field.parentNode.appendChild(errorMessage);
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
            }
        });
    </script>

@endsection