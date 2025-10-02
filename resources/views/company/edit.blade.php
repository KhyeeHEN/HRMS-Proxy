@extends('layout')

@section('title', 'Edit Company')

@section('content')

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Subsidiary</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form id="editCompanyForm" method="POST" action="{{ route('company.update', $company->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $company->title) }}" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Aliases:</label>
                                <input type="text" id="description" name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    value="{{ old('description', $company->description) }}" required>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea id="address" name="address"
                                    class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $company->address) }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">
                                Update Subsidiary
                            </button>
                            <a href="{{ route('company.index') }}" class="btn mt-4"
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
            document.querySelectorAll('#editCompanyForm input, #editCompanyForm textarea').forEach(field => {
                field.addEventListener('input', function () {
                    validateEditField(field);
                });
            });

            function validateEditField(field) {
                let fieldName = field.name;
                let fieldValue = field.value;
                let companyId = "{{ $company->id }}"; // Pass company ID for uniqueness validation if needed

                fetch("{{ route('company.validateEditField') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        field: fieldName,
                        [fieldName]: fieldValue,
                        company_id: companyId
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
