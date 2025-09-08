@extends('layout')

@section('title', 'Terminate Employee')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Remove Employee</h1>
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

        <form action="{{ route('terminate') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="identifier">Enter SSN Number or Employee ID:</label>
                <input type="text" name="identifier" id="identifier" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="termination_date">Resignation Date:</label>
                <input type="date" name="termination_date" id="termination_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="status">Reason for Removal:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="">-- Select Reason --</option>
                    <option value="Terminated">Terminated</option>
                    <option value="Resigned">Resigned</option>
                    <option value="Retired">Retired</option>
                    <option value="Deceased">Deceased</option>
                    <option value="Contract Ended">Contract Ended</option>
                </select>
            </div>

            <!-- Add upload file + reference -->
            <div class="form-group">
                <label for="file">Upload File (optional):</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>

            <div class="form-group">
                <label for="reference">Reference (optional if upload file):</label>
                <input type="text" name="reference" id="reference" class="form-control" placeholder="Enter reference">
            </div>

            <button type="submit" class="btn btn-danger">Remove Employee</button>
        </form>
    </div>
@endsection