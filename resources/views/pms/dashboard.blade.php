@extends('layout')

@section('title', 'PMS Dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">PMS Dashboard</h1>
        </div>

        <div class="row">

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    KPI
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kpiCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list-ol fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Appraisal
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appraisalCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Appraisal Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($appraisalStatus) && $appraisalStatus->count() > 0)
                                        @foreach($appraisalStatus as $appraisal)
                                            <tr>
                                                <td>{{ $appraisal->staff->name ?? 'N/A' }}</td>
                                                <td>{{ $appraisal->staff->job->name ?? 'N/A' }}</td>
                                                <td>{{ $appraisal->staff->department->name ?? 'N/A' }}</td>
                                                <td>{{ $appraisal->status }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No appraisal data available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Staff</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Supervisor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($staffList) && $staffList->count() > 0)
                                        @foreach($staffList as $staff)
                                            <tr>
                                                <td>{{ $staff->name ?? 'N/A' }}</td>
                                                <td>{{ $staff->job->name ?? 'N/A' }}</td>
                                                <td>{{ $staff->department->name ?? 'N/A' }}</td>
                                                <td>{{ $staff->supervisor->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No staff data available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection