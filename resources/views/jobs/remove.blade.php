@extends('layout')

@section('title', 'Remove Job Vacancy')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Remove Job Vacancy</h1>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('jobs.removeJob') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="job_id">Job Title:</label>
            <select name="job_id" id="job_id" class="form-control" required>
                @foreach ($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-danger">Remove Vacancy</button>
    </form>
</div>
@endsection