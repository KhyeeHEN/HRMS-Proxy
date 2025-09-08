@extends('layout')

@section('title', 'Update Job Status')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Update Job Status</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jobs.update', $job->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Job Title:</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $job->title) }}" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="vacancies">No. of Vacancies:</label>
                            <input type="number" name="vacancies" id="vacancies"
                                class="form-control @error('vacancies') is-invalid @enderror" required min="0"
                                value="{{ old('vacancies', $job->vacancies) }}">
                            @error('vacancies')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="applicants">No. of Applicants:</label>
                            <input type="number" name="applicants" id="applicants"
                                class="form-control @error('applicants') is-invalid @enderror" required min="0"
                                value="{{ old('applicants', $job->applicants) }}">
                            @error('applicants')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="interviewed">No. of Interviewed:</label>
                            <input type="number" name="interviewed" id="interviewed"
                                class="form-control @error('interviewed') is-invalid @enderror" required min="0"
                                value="{{ old('interviewed', $job->interviewed) }}">
                            @error('interviewed')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hired">No. of Hired:</label>
                            <input type="number" name="hired" id="hired"
                                class="form-control @error('hired') is-invalid @enderror" required min="0"
                                value="{{ old('hired', $job->hired) }}">
                            @error('hired')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn mt-4" style="color: #ffffff; background-color: #00aeef;">
                            Update Status
                        </button>
                        <a href="{{ route('jobs.index') }}" class="btn mt-4"
                            style="color: #ffffff; background-color: #bfbfbf;">Back to Vacancies List</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
