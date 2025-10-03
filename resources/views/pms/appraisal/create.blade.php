@extends('layout')

@section('title', 'Select Secondary Appraiser')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Assign Secondary Appraiser</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Start Appraisal for: {{ $staff->name }} ({{ $year }})
                </h6>
            </div>
            <div class="card-body">
                @include('partials.error')
                
                <form action="{{ route('appraisal.store') }}" method="POST">
                    @csrf
                    
                    {{-- Hidden fields to pass staff_id, year, and kpi_id --}}
                    <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="appraiser_1_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="kpi_id" value="{{ $kpi->id }}">

                    <div class="alert alert-info">
                        Appraiser 1 (Primary Manager): {{ Auth::user()->name }}
                    </div>

                    <div class="form-group">
                        <label for="appraiser_2_id" class="font-weight-bold">Select Appraiser 2 (Optional Secondary Manager)</label>
                        <select name="appraiser_2_id" id="appraiser_2_id" class="form-control">
                            <option value="">-- No Secondary Appraiser --</option>
                            @foreach($potentialAppraisers as $appraiser)
                                <option value="{{ $appraiser->id }}" 
                                    {{ old('appraiser_2_id') == $appraiser->id ? 'selected' : '' }}>
                                    {{ $appraiser->name }} ({{ $appraiser->access }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">The secondary appraiser will also be able to score the appraisal sections.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play"></i> Start Appraisal
                    </button>
                    <a href="{{ route('appraisal.index') }}" class="btn btn-danger">Cancel</a>
                </form>

            </div>
        </div>
    </div>
@endsection