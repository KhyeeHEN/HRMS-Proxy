@extends('layout')

@section('title', 'Upload Employees Data')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Upload Employees</h1>
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
                    <label for="employee_file" class="small mb-1">*Please
                        download the template first.</label>
                    </label></br>
                    <a href="{{ asset('templates/Employee Data.xlsx') }}" class="btn btn-sm"
                        style="background-color: #00aeef; color: #ffffff;" download id="download-template">
                        <i class="fa-solid fa-download mr-1"></i> Download Template
                    </a>
                    <input type="file" class="form-control-file form-control-sm mt-4" id="employee_file" name="file"
                        required disabled>
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <form id="employee-upload-form" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <button type="button" class="btn btn-sm" id="preview-employee"
                        style="background-color: #00aeef; color: #ffffff; margin-top: 22px;">
                        Preview
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-5">

            <h6 class="mt-2">Personal Information</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="employee-preview-personal">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Nationality</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Marital Status</th>
                            <th>SSN Num</th>
                            <th>Address 1</th>
                            <th>Address 2</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Home Phone</th>
                            <th>Mobile Phone</th>
                            <th>Private Email</th>
                            <th>Ethnicity</th>
                            <th>Immigration Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="19" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4">Work Information</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="employee-preview-work">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employment Status</th>
                            <th>Job Title</th>
                            <th>Pay Grade</th>
                            <th>Workstation</th>
                            <th>Branch</th>
                            <th>Work Phone</th>
                            <th>Work Email</th>
                            <th>Joined Date</th>
                            <th>Supervisor</th>
                            <th>Indirect Supervisors</th>
                            <th>Subsidiary</th>
                            <th>Department</th>
                            <th>Termination Date</th>
                            <th>Status</th>
                            <th>EPF No</th>
                            <th>SOCSO</th>
                            <th>LHDN No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="17" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4">Achievement</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="employee-preview-achievement">
                    <thead>
                        <tr>
                            <th>Qualification</th>
                            <th>Years of Experience</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center text-muted">No data yet. Upload file to preview.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <form action="{{ route('upload.emp') }}" method="POST" enctype="multipart/form-data" id="final-employee-import">
                @csrf
                <input type="file" name="file" id="final-employee-file" class="d-none" required>
                <label for="employee_file" class="small mt-4">*Please
                    click import after finish preview.</label><br>
                <button type="submit" class="btn btn-success">Import Employee Data</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        const placeholderMap = {
            "109": "Malaysian",
            "es1": "Full Time", "es2": "Contract", "es3": "Protege'", "es4": "Intern",
            "cMY": "Malaysia",
            "s1": "Johor", "s2": "Kedah", "s3": "Kelantan", "s4": "Melaka", "s5": "NS", "s6": "Pahang",
            "s7": "P.Pinang", "s8": "Perak", "s9": "Perlis", "s10": "Selangor", "s11": "Terengganu",
            "s12": "Sabah", "s13": "Sarawak", "s14": "WP",
            "co1": "Ktech", "co2": "Kserve", "co3": "Kinno",
            "d1": "Management", "d2": "SSO", "d3": "Presales", "d4": "Software Development",
            "d5": "Sales", "d6": "Program Management", "d7": "Post Sales", "d8": "BIOFIS",
            "e1": "Malay", "e2": "Chinese", "e3": "Indian", "e4": "Others"
        };

        function translate(val, prefix = '') {
            const key = prefix + val;
            return placeholderMap[key] || val;
        }

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

        document.getElementById('preview-employee').addEventListener('click', function () {
            const fileInput = document.getElementById('employee_file');
            const previewFileInput = document.getElementById('final-employee-file');
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

                const personalData = parseUntilBlankRows(workbook.Sheets["Personal"]);
                const workData = parseUntilBlankRows(workbook.Sheets["Work"]);
                const achievementData = parseUntilBlankRows(workbook.Sheets["Achievement"]);

                const tbodyPersonal = document.querySelector('#employee-preview-personal tbody');
                const tbodyWork = document.querySelector('#employee-preview-work tbody');
                const tbodyAchievement = document.querySelector('#employee-preview-achievement tbody');

                tbodyPersonal.innerHTML = '';
                tbodyWork.innerHTML = '';
                tbodyAchievement.innerHTML = '';

                personalData.forEach(row => {
                    const tr = document.createElement('tr');
                    const cols = ['first_name', 'last_name', 'nationality', 'birthday', 'gender', 'marital_status', 'ssn_num', 'address1', 'address2', 'city', 'country', 'state', 'postal_code', 'home_phone', 'mobile_phone', 'private_email', 'ethnicity', 'immigration_status'];
                    cols.forEach(col => {
                        const td = document.createElement('td');
                        let val = row[col] || '';
                        if (col === 'birthday' && !isNaN(val) && val !== '') {
                            const date = new Date((val - 25569) * 86400 * 1000);
                            val = date.toISOString().split('T')[0].split('-').reverse().join('/');
                        }
                        if (col === 'nationality') val = translate(val);
                        else if (col === 'country') val = translate(val, 'c');
                        else if (col === 'state') val = translate(val, 's');
                        else if (col === 'ethnicity') val = translate(val, 'e');
                        td.textContent = val;
                        tr.appendChild(td);
                    });
                    tbodyPersonal.appendChild(tr);
                });

                workData.forEach(row => {
                    const tr = document.createElement('tr');
                    const cols = ['employee_id', 'employment_status', 'job_title', 'pay_grade', 'work_station_id', 'branch', 'work_phone', 'work_email', 'joined_date', 'supervisor', 'indirect_supervisors', 'company', 'department', 'termination_date', 'status', 'epf_no', 'socso', 'lhdn_no'];
                    cols.forEach(col => {
                        const td = document.createElement('td');
                        let val = row[col] || '';
                        if ((col === 'joined_date' || col === 'termination_date') && !isNaN(val) && val !== '') {
                            const date = new Date((val - 25569) * 86400 * 1000);
                            val = date.toISOString().split('T')[0].split('-').reverse().join('/');
                        }
                        if (col === 'employment_status') val = translate(val, 'es');
                        else if (col === 'company') val = translate(val, 'co');
                        else if (col === 'department') val = translate(val, 'd');
                        td.textContent = val;
                        tr.appendChild(td);
                    });
                    tbodyWork.appendChild(tr);
                });

                achievementData.forEach(row => {
                    const tr = document.createElement('tr');
                    const cols = ['qualification', 'experience'];
                    cols.forEach(col => {
                        const td = document.createElement('td');
                        td.textContent = row[col] || '';
                        tr.appendChild(td);
                    });
                    tbodyAchievement.appendChild(tr);
                });
            };

            reader.readAsArrayBuffer(file);
            previewFileInput.classList.add('d-none');
        });
    </script>

    <script>
        document.getElementById('download-template').addEventListener('click', function () {
            const fileInput = document.getElementById('employee_file');
            fileInput.disabled = false;
            fileInput.classList.remove('is-invalid', 'border', 'border-success');
            fileInput.style.border = "none";
            fileInput.style.boxShadow = "none";
        });
    </script>
@endpush