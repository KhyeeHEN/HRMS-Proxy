@extends('layout')

@section('title', 'Upload Assets Data')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Upload Assets</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap">
            <div class="d-flex align-items-end gap-2 flex-wrap">
                <div class="form-group mb-0">
                    <label for="asset_file" class="small mb-1">*Please download the template first.</label></br>
                    <a href="{{ route('assets.template.download') }}" class="btn btn-sm"
                        style="background-color: #00aeef; color: #ffffff;" download id="download-template">
                        <i class="fa-solid fa-download mr-1"></i> Download Template
                    </a>
                    <input type="file" class="form-control-file form-control-sm mt-4" id="asset_file" name="file"
                        required disabled>
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <form id="asset-upload-form" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <button type="button" class="btn btn-sm" id="preview-asset"
                        style="background-color: #00aeef; color: #ffffff; margin-top: 22px;">
                        Preview
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-5">
            <div class="table-responsive">
                <table class="table table-bordered" id="asset-preview-table">
                    <thead>
                        <tr>
                            <th>Asset ID</th>
                            <th>Asset Name</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Model</th>
                            <th>S/N No</th>
                            <th>DOP</th>
                            <th>Warranty End</th>
                            <th>Remarks</th>
                            <th>CPU</th>
                            <th>RAM</th>
                            <th>HDD</th>
                            <th>HDD Balance</th>
                            <th>HDD2</th>
                            <th>HDD2 Balance</th>
                            <th>SSD</th>
                            <th>SSD Balance</th>
                            <th>OS</th>
                            <th>OS Key</th>
                            <th>Office</th>
                            <th>Office Key</th>
                            <th>Office Login</th>
                            <th>Antivirus</th>
                            <th>Synology</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="22" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('assets.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="final-asset-file" class="d-none" required>
                <label for="asset_file" class="small mt-4">*Please click import after finish preview.</label><br>
                <button type="submit" class="btn btn-success">Import Asset Data</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <script>
        function parseUntilBlankRows(sheet) {
            const raw = XLSX.utils.sheet_to_json(sheet, { defval: "", header: 1 });
            const final = [];
            let emptyCount = 0;

            for (const row of raw) {
                const isEmpty = row.every(cell => cell === "" || cell === null);
                if (isEmpty) {
                    emptyCount++;
                } else {
                    if (emptyCount >= 2) break;
                    emptyCount = 0;

                    // Detect instruction start
                    if (typeof row[0] === "string" && row[0].toLowerCase().includes("please")) break;

                    final.push(row);
                }
            }

            if (final.length < 2) return [];

            const headers = final[0];
            const dataRows = final.slice(1);

            return dataRows.map(row => {
                const obj = {};
                headers.forEach((key, i) => {
                    obj[key] = row[i] ?? "";
                });
                return obj;
            });
        }
        document.getElementById('preview-asset').addEventListener('click', function () {
            const fileInput = document.getElementById('asset_file');
            const previewFileInput = document.getElementById('final-asset-file');
            const file = fileInput.files[0];

            if (!file) return alert("Please choose a file first.");

            previewFileInput.classList.remove('d-none');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            previewFileInput.files = dataTransfer.files;

            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = parseUntilBlankRows(sheet);

                const tbody = document.querySelector('#asset-preview-table tbody');
                tbody.innerHTML = '';

                if (!rows.length) {
                    tbody.innerHTML = '<tr><td colspan="22" class="text-center text-muted">No data found in Excel file.</td></tr>';
                    return;
                }

                rows.forEach(row => {
                    const tr = document.createElement('tr');
                    const fields = [
                        'asset_id', 'asset_name', 'department', 'type', 'status', 'model', 'sn_no', 'dop', 'warranty_end', 'remarks',
                        'cpu', 'ram', 'hdd', 'hdd_bal', 'hdd2', 'hdd2_bal', 'ssd', 'ssd_bal','os', 'os_key', 'office', 'office_key',
                        'office_login', 'antivirus', 'synology'
                    ];
                    fields.forEach(key => {
                        const td = document.createElement('td');
                        td.textContent = row[key] || '';
                        tr.appendChild(td);
                    });
                    tbody.appendChild(tr);
                });
            };

            reader.readAsArrayBuffer(file);
            previewFileInput.classList.add('d-none');
        });

        document.getElementById('download-template').addEventListener('click', function () {
            const fileInput = document.getElementById('asset_file');
            fileInput.disabled = false;
            fileInput.classList.remove('is-invalid', 'border', 'border-success');
            fileInput.style.border = "none";
            fileInput.style.boxShadow = "none";
        });
    </script>
    <script>
        // Auto-hide success message
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.style.display = 'none';
        }, 3000); // hides after 4 seconds
    </script>
@endpush
