@extends('layout')

@section('title', 'Upload Family Data')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Upload Family</h1>
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
                    <label for="family_file" class="small mb-1">*Please download the template first.</label><br>
                    <a href="{{ asset('templates/Family Data.xlsx') }}" class="btn btn-sm"
                        style="background-color: #00aeef; color: #ffffff;" download id="download-template">
                        <i class="fa-solid fa-download mr-1"></i> Download Template
                    </a><br>
                    <label for="employee_file" class="small mt-4">*Please select the downloaded file.</label><br>
                    <input type="file" class="form-control-file form-control-sm" id="family_file" name="file" required
                        disabled>
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <form id="family-upload-form" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <button type="button" class="btn btn-sm" id="preview-family"
                        style="background-color: #00aeef; color: #ffffff; margin-top: 22px;">
                        Preview
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-5">
            <h6 class="mt-2">Spouse Information</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="family-preview-spouse">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>SSN</th>
                            <th>Spouse Name</th>
                            <th>Spouse Status</th>
                            <th>Spouse IC</th>
                            <th>Spouse Tax</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4">Child Information</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="family-preview-child">
                    <thead>
                        <tr>
                            <th>Child Under 18</th>
                            <th>Tax Relief (Below 18)</th>
                            <th>Child Above 18</th>
                            <th>Tax Relief (Above 18)</th>
                            <th>Child 1</th>
                            <th>Child 2</th>
                            <th>Child 3</th>
                            <th>Child 4</th>
                            <th>Child 5</th>
                            <th>Child 6</th>
                            <th>Child 7</th>
                            <th>Child 8</th>
                            <th>Child 9</th>
                            <th>Child 10</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="14" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4">Emergency Contact</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="family-preview-emergency">
                    <thead>
                        <tr>
                            <th>Contact 1 Name</th>
                            <th>Contact 1 No</th>
                            <th>Contact 1 Relationship</th>
                            <th>Contact 1 Address</th>
                            <th>Contact 2 Name</th>
                            <th>Contact 2 No</th>
                            <th>Contact 2 Relationship</th>
                            <th>Contact 2 Address</th>
                            <th>Contact 3 Name</th>
                            <th>Contact 3 No</th>
                            <th>Contact 3 Relationship</th>
                            <th>Contact 3 Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="12" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form action="{{ route('upload.family') }}" method="POST" enctype="multipart/form-data"
                id="final-family-import">
                @csrf
                <input type="file" name="file" id="final-family-file" class="d-none" required>
                <label for="employee_file" class="small mt-4">*Please
                    click import after finish preview.</label><br>
                <button type="submit" class="btn btn-success">Import Family Data</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        // Your existing placeholderMap and translate function (if needed for family, you can add)

        function parseUntilBlankRows(sheet) {
            const raw = XLSX.utils.sheet_to_json(sheet, { defval: "", header: 1 });
            const final = [];
            let emptyCount = 0;

            for (const row of raw) {
                const isEmpty = row.every(cell => cell === "" || cell === null);
                if (isEmpty) {
                    emptyCount++;
                } else {
                    if (emptyCount >= 2) break;  // Stop if 2 consecutive empty rows
                    emptyCount = 0;
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

        document.getElementById('preview-family').addEventListener('click', function () {
            const fileInput = document.getElementById('family_file');
            const previewFileInput = document.getElementById('final-family-file');
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

                // Parse each sheet with the same logic
                const spouseData = parseUntilBlankRows(workbook.Sheets['Spouse']);
                const childData = parseUntilBlankRows(workbook.Sheets['Child']);
                const emergencyData = parseUntilBlankRows(workbook.Sheets['Emergency']);

                const spouseBody = document.querySelector('#family-preview-spouse tbody');
                const childBody = document.querySelector('#family-preview-child tbody');
                const emergencyBody = document.querySelector('#family-preview-emergency tbody');

                spouseBody.innerHTML = '';
                childBody.innerHTML = '';
                emergencyBody.innerHTML = '';

                spouseData.forEach(row => {
                    spouseBody.innerHTML += `<tr>
                                        <td>${row['name'] || ''}</td>
                                        <td>${row['ssn_num'] || ''}</td>
                                        <td>${row['spouse_name'] || ''}</td>
                                        <td>${row['spouse_status'] || ''}</td>
                                        <td>${row['spouse_ic'] || ''}</td>
                                        <td>${row['spouse_tax'] || ''}</td>
                                    </tr>`;
                });

                childData.forEach(row => {
                    childBody.innerHTML += `<tr>
                                        <td>${row['noc_under'] || ''}</td>
                                        <td>${row['tax_under'] || ''}</td>
                                        <td>${row['noc_above'] || ''}</td>
                                        <td>${row['tax_above'] || ''}</td>
                                        <td>${row['child1'] || ''}</td>
                                        <td>${row['child2'] || ''}</td>
                                        <td>${row['child3'] || ''}</td>
                                        <td>${row['child4'] || ''}</td>
                                        <td>${row['child5'] || ''}</td>
                                        <td>${row['child6'] || ''}</td>
                                        <td>${row['child7'] || ''}</td>
                                        <td>${row['child8'] || ''}</td>
                                        <td>${row['child9'] || ''}</td>
                                        <td>${row['child10'] || ''}</td>
                                    </tr>`;
                });

                emergencyData.forEach(row => {
                    emergencyBody.innerHTML += `<tr>
                                        <td>${row['contact1_name'] || ''}</td>
                                        <td>${row['contact1_no'] || ''}</td>
                                        <td>${row['contact1_rel'] || ''}</td>
                                        <td>${row['contact1_add'] || ''}</td>
                                        <td>${row['contact2_name'] || ''}</td>
                                        <td>${row['contact2_no'] || ''}</td>
                                        <td>${row['contact2_rel'] || ''}</td>
                                        <td>${row['contact2_add'] || ''}</td>
                                        <td>${row['contact3_name'] || ''}</td>
                                        <td>${row['contact3_no'] || ''}</td>
                                        <td>${row['contact3_rel'] || ''}</td>
                                        <td>${row['contact3_add'] || ''}</td>
                                    </tr>`;
                });
            };

            reader.readAsArrayBuffer(file);
            previewFileInput.classList.add('d-none');
        });
    </script>
    <script>
        document.getElementById('download-template').addEventListener('click', function () {
            const fileInput = document.getElementById('family_file');
            fileInput.disabled = false;
            fileInput.classList.remove('is-invalid', 'border', 'border-success');
            fileInput.style.border = "none";
            fileInput.style.boxShadow = "none";
        });
    </script>
@endpush