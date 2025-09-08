@extends('layout')

@section('title', 'Employees in ' . $department->name)

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Employees in {{ $department->name }}</h2>

        <a href="{{ route('department.index') }}" class="btn btn-secondary mb-4">← Back to Departments</a>

        @if($department->employees->isEmpty())
            <p>No employees found in this department.</p>
        @else
            <div class="card shadow">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Work Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($department->employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>
                                        {{ $employee->first_name }}
                                        {{ $employee->gender == 'Male' ? 'Bin' : 'Binti' }}
                                        {{ $employee->last_name }}
                                    </td>
                                    <td>{{ $employee->job_title ?? '-' }}</td>
                                    <td>{{ $employee->work_email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection