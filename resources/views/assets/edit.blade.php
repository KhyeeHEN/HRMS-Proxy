@extends('layout')

@section('title', 'Edit Asset')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Asset</h1>
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
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('assets.update', $asset->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <label>Asset ID</label>
                                <input type="text" name="asset_id" class="form-control mb-2" value="{{ old('asset_id', $asset->asset_id) }}">

                                <label>Asset Name</label>
                                <input type="text" name="asset_name" class="form-control mb-2" value="{{ old('asset_name', $asset->asset_name) }}" required>

                                <label>Assigned Employee</label>
                                <select name="employee_id" class="form-control mb-2">
                                    <option value="">N/A</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" 
                                            {{ optional($asset->currentAssignment)->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label>Department</label>
                                <select name="department" class="form-control mb-2">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $asset->department == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label>Type</label>
                                <select name="type" class="form-control mb-2">
                                    <option value="">Select Type</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $asset->type == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label>Status</label>
                                <select name="status" class="form-control mb-2">
                                    <option value="">Select</option>
                                    <option value="Active" {{ $asset->status === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Idle" {{ $asset->status === 'Idle' ? 'selected' : '' }}>Idle</option>
                                    <option value="Damaged" {{ $asset->status === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>

                                <label>Model</label>
                                <input type="text" name="model" class="form-control mb-2" value="{{ old('model', $asset->model) }}">

                                <label>S/N No</label>
                                <input type="text" name="sn_no" class="form-control mb-2" value="{{ old('sn_no', $asset->sn_no) }}">

                                <label>CPU</label>
                                <input type="text" name="cpu" class="form-control mb-2" value="{{ old('cpu', $asset->cpu) }}">



                            </div>
                            <div class="col-md-4">
                                <label>RAM</label>
                                <input type="text" name="ram" class="form-control mb-2" value="{{ old('ram', $asset->ram) }}">

                                <label>HDD</label>
                                <input type="text" name="hdd" class="form-control mb-2" value="{{ old('hdd', $asset->hdd) }}">

                                <label>HDD Balance</label>
                                <input type="text" name="hdd_bal" class="form-control mb-2" value="{{ old('hdd_bal', $asset->hdd_bal) }}">

                                <label>HDD2</label>
                                <input type="text" name="hdd2" class="form-control mb-2" value="{{ old('hdd2', $asset->hdd2) }}">

                                <label>HDD2 Balance</label>
                                <input type="text" name="hdd2_bal" class="form-control mb-2" value="{{ old('hdd2_bal', $asset->hdd2_bal) }}">

                                <label>SSD</label>
                                <input type="text" name="ssd" class="form-control mb-2" value="{{ old('ssd', $asset->ssd) }}">

                                <label>SSD Balance</label>
                                <input type="text" name="ssd_bal" class="form-control mb-2" value="{{ old('ssd_bal', $asset->ssd_bal) }}">

                                <label>OS</label>
                                <input type="text" name="os" class="form-control mb-2" value="{{ old('os', $asset->os) }}">

                                <label>OS Key</label>
                                <input type="text" name="os_key" class="form-control mb-2" value="{{ old('os_key', $asset->os_key) }}">

                            </div>
                            <div class="col-md-4">
                                <label>Office</label>
                                <input type="text" name="office" class="form-control mb-2" value="{{ old('office', $asset->office) }}">

                                <label>Office Key</label>
                                <input type="text" name="office_key" class="form-control mb-2" value="{{ old('office_key', $asset->office_key) }}">

                                <label>Office Login</label>
                                <input type="text" name="office_login" class="form-control mb-2" value="{{ old('office_login', $asset->office_login) }}">
                                
                                <label>Antivirus</label>
                                <input type="text" name="antivirus" class="form-control mb-2" value="{{ old('antivirus', $asset->antivirus) }}">

                                <label>Synology</label>
                                <input type="text" name="synology" class="form-control mb-2" value="{{ old('synology', $asset->synology) }}">

                                <label>DOP (Year)</label>
                                <input type="number" name="dop" class="form-control mb-2" value="{{ old('dop', $asset->dop) }}">

                                <label>Warranty End</label>
                                <input type="text" name="warranty_end" class="form-control mb-2" value="{{ old('warranty_end', $asset->warranty_end) }}">

                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control mb-2">{{ old('remarks', $asset->remarks) }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Update Asset</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) alert.style.display = 'none';
    }, 4000); // hides after 4 seconds
</script>

@endsection