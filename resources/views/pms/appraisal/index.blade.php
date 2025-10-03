@extends('layout')

@section('title', 'Appraisal List')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Appraisal Management ({{ $currentYear }})</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Staff Appraisal Overview</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <table class="table table-bordered" id="appraisalTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Staff ID</th>
                            <th>Current Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffList as $item)
                            <tr>
                                <td>{{ $item['staff']->name }}</td>
                                <td>{{ $item['staff']->id }}</td>
                                <td>
                                    <span class="badge 
                                                @if($item['status'] === 'Not Started') badge-danger
                                                @elseif($item['status'] === 'draft' || $item['status'] === 'in progress') badge-warning
                                                @else badge-success
                                                @endif">
                                        {{ ucwords($item['status']) }}
                                    </span>
                                </td>
                                <td>
                                    @if($item['appraisal'])
                                        {{-- Link to the existing appraisal SHOW page --}}
                                        <a href="{{ route('appraisal.show', $item['appraisal']->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View/Continue
                                        </a>
                                    @else
                                        {{-- We pass the staff ID to the create route --}}
                                        <a href="{{ route('appraisal.create', ['staff_id' => $item['staff']->id, 'year' => $currentYear]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Start Appraisal
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection