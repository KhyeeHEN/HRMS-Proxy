@extends('layout')

@section('title', 'View KPIs')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">KPI Management</h1>
            @if(in_array(auth()->user()->access, ['Admin', 'HR', 'Manager']))
                <a href="{{ route('kpi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Create New KPI
                </a>
            @endif
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">KPI List</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="mb-4">
                    {{-- Button to Open Filter Modal --}}
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#filterModal">
                        <i class="fas fa-filter"></i> Advanced Filters
                    </button>

                    {{-- Display active filters by iterating over the filter arrays --}}
                    @if(is_array($filterStatus) || is_array($filterDepartment))
                        <span class="ml-3 text-muted">Active Filters:</span>

                        {{-- 1. Display Active STATUS Filters --}}
                        @if(is_array($filterStatus) && !empty($filterStatus))
                            @foreach($filterStatus as $status)
                                <span class="badge badge-primary">{{ ucfirst($status) }}</span>
                            @endforeach
                        @endif

                        {{-- 2. Display Active DEPARTMENT Filters --}}
                        @if(is_array($filterDepartment) && !empty($filterDepartment))
                            @foreach($filterDepartment as $deptId)
                                @php
                                    // Find the department object for the current ID to get its name
                                    $deptName = $departments->where('id', $deptId)->first()->name ?? 'Unknown';
                                @endphp
                                <span class="badge badge-secondary">{{ $deptName }}</span>
                            @endforeach
                        @endif

                        <a href="{{ route('kpi.index') }}" class="btn btn-sm btn-danger ml-2">Clear All</a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                @if(auth()->user()->access === 'Manager')
                                    <th>Department/Unit</th>
                                    <th>Assigned Staff</th>
                                @elseif(in_array(auth()->user()->access, ['Admin', 'HR']))
                                    <th>Department/Unit</th>
                                    <th>Assigned Staff</th>
                                    <th>Manager</th>
                                @else
                                    <th>Department/Unit</th>
                                    <th>Assigned To</th>
                                    <th>Manager</th>
                                @endif
                                <th>Goals</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kpis as $kpi)
                                <tr>
                                    @if(auth()->user()->access === 'Manager')
                                        <td>
                                            {{ optional($kpi->department)->name ?? 'N/A' }}
                                            @if($kpi->unit)
                                                - {{ $kpi->unit }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($kpi->assignedStaff)->name ?? 'Not Assigned' }}
                                        </td>
                                    @elseif(in_array(auth()->user()->access, ['Admin', 'HR']))
                                        <td>
                                            {{ optional($kpi->department)->name ?? 'N/A' }}
                                            @if($kpi->unit)
                                                - {{ $kpi->unit }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($kpi->assignedStaff)->name ?? 'Not Assigned' }}
                                        </td>
                                        <td>{{ optional($kpi->manager)->name ?? 'N/A' }}</td>
                                    @else
                                        <td>
                                            {{ optional($kpi->department)->name ?? 'N/A' }}
                                            @if($kpi->unit)
                                                - {{ $kpi->unit }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($kpi->assignedStaff)->name ?? 'Not Assigned' }}
                                        </td>
                                        <td>{{ optional($kpi->manager)->name ?? 'N/A' }}</td>
                                    @endif

                                    <td>
                                        @php
                                            // Build the tooltip content by concatenating all goals
                                            $goalList = $kpi->goals->map(function ($goal, $index) {
                                                // Display Goal number, Goal name, and its Weightage
                                                return 'Goal ' . ($index + 1) . ': ' . $goal->goal . ' (' . $goal->weightage . '%)';
                                            })->implode("\n"); // Use newline character (\n) for line breaks in the tooltip
                                        @endphp

                                        <span title="{{ $goalList }}" style="cursor: help;">
                                            <strong>{{ $kpi->goals->count() }}</strong> goals defined
                                        </span>
                                    </td>

                                    <td>
                                        {{-- NEW: Check for appraisal_id first to assign 'Archived' status --}}
                                        @if($kpi->appraisal_id)
                                            <span class="badge badge-secondary">Archived</span>
                                        @elseif($kpi->status === 'draft')
                                            <span class="badge badge-secondary">Draft</span>
                                        @elseif($kpi->status === 'for review')
                                            <span class="badge badge-warning">For Review</span>
                                        @elseif($kpi->status === 'accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($kpi->status === 'declined')
                                            <span class="badge badge-danger">Declined</span>
                                        @elseif($kpi->status === 'template')
                                            <span class="badge badge-info">Template</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('kpi.show', $kpi->id) }}" class="btn btn-info btn-sm"><i
                                                class="fas fa-eye"></i> View</a>

                                        {{-- Editable if: Admin/HR OR Manager who owns the KPI --}}
                                        @if(
                                                in_array(auth()->user()->access, ['Admin', 'HR']) ||
                                                (auth()->user()->access === 'Manager' && $kpi->manager_id === auth()->id())
                                            )
                                            @if(in_array($kpi->status, ['draft', 'for review', 'template', 'declined']) || $kpi->accepted === 'rejected')
                                                <a href="{{ route('kpi.edit', $kpi->id) }}" class="btn btn-warning btn-sm"><i
                                                        class="fa fa-edit"></i> Edit</a>
                                                <form action="{{ route('kpi.destroy', $kpi->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this KPI?')"><i
                                                            class="fas fa-trash"></i> Delete</button>
                                                </form>
                                            @endif

                                            @if($kpi->status === 'template')
                                                <a href="{{ route('kpi.assign.show', $kpi->id) }}" class="btn btn-success btn-sm"><i
                                                        class="fa fa-user-plus"></i> Assign</a>
                                            @endif
                                        @elseif(in_array(auth()->user()->access, ['Staff', 'Employee']) && $kpi->assigned_to_staff_id === auth()->id())
                                            @if($kpi->status === 'for review')
                                                <form action="{{ route('kpi.accept', $kpi->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Are you sure you want to accept this KPI? This action cannot be undone.')">
                                                        <i class="fas fa-check"></i> Accept
                                                    </button>
                                                </form>

                                                <form action="{{ route('kpi.request-revision', $kpi->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-wrench"></i>
                                                        Request Revision</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $kpis->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- **NEW: Filter Modal --}}
    @include('partials.kpi-filter-modal')
@endsection