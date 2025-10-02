@extends('layout')

@section('title', 'Job Vacancies')

<link href="{{ asset('css/search.css') }}" rel="stylesheet">
<style>
    .form-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .form-label {
        font-weight: bold;
        font-size: 0.9rem;
    }
</style>

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <script>
                $(document).ready(function () {
                    $('#addJobModal').modal('show');
                });
            </script>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 text-gray-800">Job Vacancies</h1>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                <a href="#" class="btn btn-sm shadow-sm" style="background-color:#00aeef; color:white;" data-toggle="modal"
                    data-target="#addJobModal">
                    <i class="fas fa-plus fa-sm mr-1"></i> Add Job
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Job Title</th>
                            <th>No. of Vacancies</th>
                            <th>No. of Hired</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td>{{ ($jobs->currentPage() - 1) * $jobs->perPage() + $loop->iteration }}</td>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->vacancies }}</td>
                                <td>{{ $job->hired }}</td>
                                <td>
                                    <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure to delete this job?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No jobs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3 mb-3">
                {{ $jobs->appends(['per_page' => request('per_page')])->links() }}
            </div>
        </div>
    </div>

    <!-- Add Job Modal -->
    <div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-labelledby="addJobModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('jobs.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vacancy</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @php
                            $fields = [
                                'title' => 'Job Title',
                                'vacancies' => 'No. of Vacancies',
                                'applicants' => 'No. of Applicants',
                                'interviewed' => 'No. of Interviewed',
                                'hired' => 'No. of Hired',
                            ];
                        @endphp

                        @foreach($fields as $field => $label)
                            <div class="form-group">
                                <label class="form-label" for="{{ $field }}">{{ $label }}:</label>
                                <input
                                    type="{{ in_array($field, ['vacancies', 'applicants', 'interviewed', 'hired']) ? 'number' : 'text' }}"
                                    class="form-control" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                                    required>
                                @error($field)
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background-color:#00aeef; color:white;">Add Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap & Pagination JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('pagination').addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    </script>
@endsection