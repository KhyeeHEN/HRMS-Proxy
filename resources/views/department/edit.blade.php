@extends('layout')

@section('title', 'Edit department')

@section('content')

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Department</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form id="editdepartmentForm" method="POST" action="{{ route('department.update', $department->id) }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $department->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">
                                Update Department
                            </button>
                            <a href="{{ route('department.index') }}" class="btn mt-4"
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
            document.querySelectorAll('#editdepartmentForm input, #editdepartmentForm textarea').forEach(field => {
                field.addEventListener('input', function () {
                    validateEditField(field);
                });
            });

            function validateEditField(field) {
                let fieldName = field.name;
                let fieldValue = field.value;
                let departmentId = "{{ $department->id }}"; // Pass department ID for uniqueness validation if needed

                fetch("{{ route('department.validateEditField') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        field: fieldName,
                        [fieldName]: fieldValue,
                        department_id: departmentId
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
