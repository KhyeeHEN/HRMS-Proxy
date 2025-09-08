<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Make sure to import your Employee model
use App\Models\Company;
use App\Models\Department;
use App\Models\Country;
use App\Models\E_Status;
use App\Models\Ethnicity;
use App\Models\JobTitle;
use App\Models\Nationality;
use App\Models\State;
use App\Models\Family;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetAssignmentHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        // Get all employees who are not terminated
        $employees = Employee::whereNull('termination_date')
            ->with(['companyStructure', 'departmentName', 'employmentStatus', 'jobTitle'])
            ->get();

        // Fetch unique dropdown options from the Employee model
        $companies = Company::all();
        $departments = Department::all();
        $employment_statuses = E_Status::all();
        $job_titles = JobTitle::all();
        $states = State::all();
        $ethnicities = Ethnicity::all();
        $countries = Country::all();
        $nationalities = Nationality::all();
        $familyMembers = Family::all();

        // Get employees who are supervisors
        $supervisors = Employee::whereHas('employmentStatus', function ($query) {
            $query->where('id', 2); // Adjust based on actual supervisor employment status
        })->get();

        return view('employees', compact(
            'employees',
            'companies',
            'departments',
            'employment_statuses',
            'job_titles',
            'states',
            'ethnicities',
            'countries',
            'nationalities',
            'supervisors',
            'familyMembers'
        ));
    }

    public function personal()
    {
        // Get all employees who are not terminated and eager load the company structure
        $employees = Employee::whereNull('termination_date')
            ->with('companyStructure')
            ->get();

        // Pass the employees data to the view
        return view('personal', compact('employees'));
    }

    public function indexPast(Request $request)
    {
        $sort = $request->query('sort', 'desc'); // default sort desc
        $sortOrder = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';

        $employees = Employee::whereNotNull('termination_date')
            ->with(['companyStructure', 'departmentName', 'employmentStatus', 'jobTitle'])
            ->orderBy('termination_date', $sortOrder)
            ->get();

        return view('employees-past', compact('employees', 'sortOrder'));
    }

    // Add this method to EmployeeController
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                // Employee data validations
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'ssn_num' => 'required|string|max:255',
                'nationality' => 'nullable|string|max:255',
                'birthday' => 'nullable|date',
                'gender' => 'nullable|string|max:10',
                'marital_status' => 'nullable|string|max:255',
                'ethnicity' => 'nullable|exists:ethnicity,id',
                'address1' => 'nullable|string|max:255',
                'address2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|exists:country,code',
                'postal_code' => 'nullable|string|max:20',
                'state' => 'nullable|exists:states,id',
                'home_phone' => 'nullable|string|max:20',
                'mobile_phone' => 'nullable|string|max:20',
                'private_email' => 'nullable|email|max:255',
                'epf_no' => 'nullable|string|max:255',
                'socso' => 'nullable|string|max:255',
                'lhdn_no' => 'nullable|string|max:255',
                'employee_id' => 'nullable|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'company' => 'nullable|exists:companystructures,id',
                'department' => 'nullable|string|max:255',
                'joined_date' => 'nullable|date',
                'expiry_date' => 'nullable|date', // New expiry_date field
                'work_station_id' => 'nullable|string|max:255',
                'branch' => 'nullable|string|max:255',
                'work_phone' => 'nullable|string|max:20',
                'work_email' => 'nullable|email|max:255',
                'employment_status' => 'nullable|exists:employmentstatus,id',
                'status' => 'nullable|string|max:255',
                'approver1' => 'nullable|string|max:255',
                'approver2' => 'nullable|string|max:255',
                'approver3' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'qualification' => 'nullable|string|max:255',
                'experience' => 'nullable|string|max:255',
                'termination_date' => 'nullable|date', // Validate termination_date as nullable date
                'folder' => 'nullable|string|max:255',
                // Photo validation
                'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048' // Validate photo

            ]);

            // Create a new employee record with validated data
            $employee = Employee::create($validatedData);

            return redirect()->route('employees')->with('status', 'Employee created successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error while saving the employee data.',
                'error' => $e->getMessage(), // 👈 this shows the real reason
            ], 500);
        }
    }


    public function show($lastSixDigits, $employmentStatus, $firstName)
    {
        // Fetch the employee details
        $employee = Employee::with([
            'stateName',
            'companyStructure',
            'ethnicityName',
            'employmentStatus',
            'jobTitle',
            'payGrade',
            'countryName',
            'national',
            'familyDetails',
            'departmentName'
        ])
            ->whereRaw('SUBSTRING(ssn_num, -7) = ?', [$lastSixDigits])
            ->where('employment_status', $employmentStatus)
            ->whereRaw('LOWER(REPLACE(first_name, " ", "_")) = ?', [strtolower($firstName)])
            ->firstOrFail();

        $firstName = preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));
        $folderName = $lastSixDigits . '-' . $firstName;

        // Get the folder path for the employee
        $folderPath = public_path("emp/" . $folderName);

        // Initialize an empty array for files and references
        $files = [];
        $fileReferences = [];

        if (file_exists($folderPath)) {
            $files = array_diff(scandir($folderPath), ['..', '.']); // Fetch all files excluding '.' and '..'
        }

        // Fetch file details (file_name and description) from the database
        $dbFiles = DB::table('files')->where('employee_id', $employee->id)->get();

        // Combine files and descriptions
        foreach ($files as $file) {
            // Look up the file in the database to get the description (reference)
            $fileReferences[$file] = $dbFiles->firstWhere('file_name', $file)?->description ?? 'No reference provided';
        }

        $unassignedAssets = \App\Models\Asset::whereDoesntHave('currentAssignment')->get();

        // Fetch assigned assets
        $assignedAssets = AssetAssignment::with(['asset.category', 'asset.departmentInfo'])
            ->where('employee_id', $employee->id)
            ->get();
        // Pass both employee details, files, and references to the view
        return view('details', compact('employee', 'files', 'fileReferences', 'dbFiles', 'assignedAssets', 'unassignedAssets'));
    }

    public function filterEmp(Request $request)
    {
        $filters = $request->input('filters', []);

        $query = Employee::query();

        // Exclude employees with termination_date
        $query->whereNull('termination_date');

        // Apply filters based on request
        if (in_array('ktech', $filters)) {
            $query->where('company', '1'); // Kridentia Tech
        }
        if (in_array('kserv', $filters)) {
            $query->where('company', '2'); // Kridentia Integrated Services
        }
        if (in_array('kinno', $filters)) {
            $query->where('company', '3'); // Kridentia Innovations
        }
        if (in_array('fulltime', $filters)) {
            $query->where('employment_status', '1');
        }
        if (in_array('contract', $filters)) {
            $query->where('employment_status', '2');
        }
        if (in_array('protege', $filters)) {
            $query->where('employment_status', '3');
        }
        if (in_array('intern', $filters)) {
            $query->where('employment_status', '4');
        }
        if (in_array('management', $filters)) {
            $query->where('department', '1');
        }
        if (in_array('sso', $filters)) {
            $query->where('department', '2');
        }
        if (in_array('presales', $filters)) {
            $query->where('department', '3');
        }
        if (in_array('sd', $filters)) {
            $query->where('department', '4');
        }
        if (in_array('sales', $filters)) {
            $query->where('department', '5');
        }
        if (in_array('pm', $filters)) {
            $query->where('department', '6');
        }
        if (in_array('postsales', $filters)) {
            $query->where('department', '7');
        }
        if (in_array('biofis', $filters)) {
            $query->where('department', '8');
        }

        $employees = $query->get();

        // Add all required data
        $companies = Company::all();
        $departments = Department::all();
        $employment_statuses = E_Status::all();
        $job_titles = JobTitle::all();
        $states = State::all();
        $ethnicities = Ethnicity::all();
        $countries = Country::all();
        $nationalities = Nationality::all();
        $familyMembers = Family::all();

        return view('employees', compact(
            'employees',
            'companies',
            'departments',
            'employment_statuses',
            'job_titles',
            'states',
            'ethnicities',
            'countries',
            'nationalities',
            'familyMembers'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Get only active employees matching the query
        $employees = Employee::where(function ($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%");
        })
            ->where('status', 'Active') // Only active employees
            ->get();

        // Fetch supporting data required by the view
        $companies = Company::all();
        $departments = Department::all();
        $employment_statuses = E_Status::all();
        $job_titles = JobTitle::all();
        $states = State::all();
        $ethnicities = Ethnicity::all();
        $countries = Country::all();
        $nationalities = Nationality::all();
        $familyMembers = Family::all();

        // Get supervisors who have a specific employment status (example: id 2)
        $supervisors = Employee::whereHas('employmentStatus', function ($q) {
            $q->where('id', 2);
        })->get();

        return view('employees', compact(
            'employees',
            'companies',
            'departments',
            'employment_statuses',
            'job_titles',
            'states',
            'ethnicities',
            'countries',
            'nationalities',
            'supervisors',
            'familyMembers'
        ));
    }

    public function edit($lastSixDigits, $employmentStatus, $firstName)
    {
        // Fetch the employee data
        $employee = Employee::with([
            'stateName',
            'companyStructure',
            'ethnicityName',
            'employmentStatus',
            'jobTitle',
            'payGrade',
            'countryName',
            'national',
            'familyDetails'
        ])
            ->whereRaw('SUBSTRING(ssn_num, -7) = ?', [$lastSixDigits])
            ->where('employment_status', $employmentStatus)
            ->whereRaw('LOWER(REPLACE(first_name, " ", "_")) = ?', [strtolower($firstName)])
            ->firstOrFail();

        // Fetch options for dropdowns
        $states = State::pluck('name', 'id');
        $companies = Company::pluck('title', 'id');
        $ethnicities = Ethnicity::pluck('name', 'id');
        $statuses = E_Status::pluck('name', 'id');
        $countries = Country::pluck('name', 'code');
        $nationals = Nationality::pluck('name', 'id');

        // === Begin merged part from show() ===

        $firstName = preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));
        $folderName = $lastSixDigits . '-' . $firstName;

        $folderPath = public_path("emp/" . $folderName);

        $files = [];
        $fileReferences = [];

        if (file_exists($folderPath)) {
            $files = array_diff(scandir($folderPath), ['..', '.']);
        }

        $dbFiles = DB::table('files')->where('employee_id', $employee->id)->get();

        foreach ($files as $file) {
            $fileReferences[$file] = $dbFiles->firstWhere('file_name', $file)?->description ?? 'No reference provided';
        }

        // === End merged part ===

        // Return all data to view
        return view('edit', compact(
            'employee',
            'states',
            'companies',
            'ethnicities',
            'statuses',
            'countries',
            'nationals',
            'files',
            'fileReferences',
            'dbFiles'
        ));
    }

    public function update(Request $request, $lastSixDigits, $employmentStatus, $firstName)
    {
        // Collect input data
        $input = $request->all();

        // Sanitize termination_date field
        if (isset($input['termination_date']) && $input['termination_date'] !== 'N/A') {
            try {
                // Attempt to create a Carbon instance from the provided date
                $terminationDate = Carbon::parse($input['termination_date']);
                $input['termination_date'] = $terminationDate->format('Y-m-d');
            } catch (\Exception $e) {
                // Handle invalid date format
                Log::warning('Invalid termination_date format:', [$input['termination_date']]);
                $input['termination_date'] = null; // Set to null if invalid
            }
        } else {
            // If 'N/A' or not set, set termination_date to null
            $input['termination_date'] = null;
        }

        // Merge sanitized input back into the request
        $request->merge($input);

        // Validate the sanitized data
        $request->validate([
            // Employee data validations
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'ssn_num' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:255',
            'ethnicity' => 'nullable|exists:ethnicity,id',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'state' => 'nullable|exists:states,id',
            'country' => 'nullable|exists:country,code',
            'home_phone' => 'nullable|string|max:20',
            'mobile_phone' => 'nullable|string|max:20',
            'private_email' => 'nullable|email|max:255',
            'epf_no' => 'nullable|string|max:255',
            'socso' => 'nullable|string|max:255',
            'lhdn_no' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|exists:companystructures,id',
            'department' => 'nullable|string|max:255',
            'joined_date' => 'nullable|date',
            'expiry_date' => 'nullable|date', // New expiry_date field
            'work_station_id' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'work_phone' => 'nullable|string|max:20',
            'work_email' => 'nullable|email|max:255',
            'employment_status' => 'nullable|exists:employmentstatus,id',
            'status' => 'nullable|string|max:255',
            'approver1' => 'nullable|string|max:255',
            'approver2' => 'nullable|string|max:255',
            'approver3' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'termination_date' => 'nullable|date', // Validate termination_date as nullable date
            'folder' => 'nullable|string|max:255',
            // Family Details validations
            'spouse_name' => 'nullable|string|max:255',
            'spouse_status' => 'nullable|string|max:255',
            'spouse_ic' => 'nullable|string|max:255',
            'spouse_tax' => 'nullable|string|max:255',
            'noc_under' => 'nullable|string|max:255',
            'tax_under' => 'nullable|string|max:255',
            'noc_above' => 'nullable|string|max:255',
            'tax_above' => 'nullable|string|max:255',
            'child1' => 'nullable|string|max:255',
            'child2' => 'nullable|string|max:255',
            'child3' => 'nullable|string|max:255',
            'child4' => 'nullable|string|max:255',
            'child5' => 'nullable|string|max:255',
            'child6' => 'nullable|string|max:255',
            'child7' => 'nullable|string|max:255',
            'child8' => 'nullable|string|max:255',
            'child9' => 'nullable|string|max:255',
            'child10' => 'nullable|string|max:255',
            'contact1_name' => 'nullable|string|max:255',
            'contact1_no' => 'nullable|string|max:255',
            'contact1_rel' => 'nullable|string|max:255',
            'contact1_add' => 'nullable|string|max:255',
            'contact2_name' => 'nullable|string|max:255',
            'contact2_no' => 'nullable|string|max:255',
            'contact2_rel' => 'nullable|string|max:255',
            'contact2_add' => 'nullable|string|max:255',
            'contact3_name' => 'nullable|string|max:255',
            'contact3_no' => 'nullable|string|max:255',
            'contact3_rel' => 'nullable|string|max:255',
            'contact3_add' => 'nullable|string|max:255',
            // Photo validation
            'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048' // Validate photo
        ]);

        // Find the employee by the last four digits of the SSN
        $employee = Employee::whereRaw('SUBSTRING(ssn_num, -7) = ?', [$lastSixDigits])
            ->where('employment_status', $employmentStatus)
            ->whereRaw('LOWER(REPLACE(first_name, " ", "_")) = ?', [strtolower($firstName)])
            ->firstOrFail();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageData = file_get_contents($image);
            $employee->photo = $imageData;
        }

        $employee->update($input);

        // Update employee data
        $employee->update($request->only([
            'first_name',
            'last_name',
            'ssn_num',
            'nationality',
            'birthday',
            'gender',
            'marital_status',
            'ethnicity',
            'address1',
            'address2',
            'city',
            'postal_code',
            'state',
            'country',
            'home_phone',
            'mobile_phone',
            'private_email',
            'epf_no',
            'socso',
            'lhdn_no',
            'employee_id',
            'job_title',
            'company',
            'department',
            'joined_date',
            'expiry_date', // New expiry_date field
            'termination_date',
            'pay_grade',
            'work_station_id',
            'branch',
            'work_phone',
            'work_email',
            'supervisor',
            'indirect_supervisors',
            'employment_status',
            'status',
            'approver1',
            'approver2',
            'approver3',
            'notes',
            'qualification',
            'experience',
            'photo',
            'folder'
        ]));

        // Update family details if they exist
        if ($employee->familyDetails) {
            $employee->familyDetails->update($request->only([
                'spouse_name',
                'spouse_status',
                'spouse_ic',
                'spouse_tax',
                'noc_under',
                'tax_under',
                'noc_above',
                'tax_above',
                'child1',
                'child2',
                'child3',
                'child4',
                'child5',
                'child6',
                'child7',
                'child8',
                'child9',
                'child10',
                'contact1_name',
                'contact1_no',
                'contact1_rel',
                'contact1_add',
                'contact2_name',
                'contact2_no',
                'contact2_rel',
                'contact2_add',
                'contact3_name',
                'contact3_no',
                'contact3_rel',
                'contact3_add'
            ]));
        }

        Log::info('Updated Employee:', [$request->all()]);
        // Redirect back to the employee details page with a success message
        return redirect()->route('employees.show', [
            'lastSixDigits' => substr($employee->ssn_num, -7),
            'employmentStatus' => $employee->employment_status,
            'firstName' => str_replace(' ', '_', $employee->first_name)
        ])->with('success', 'Employee details updated successfully!');
    }


    public function terminateForm()
    {
        return view('terminate');
    }

    public function terminate(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:255',
            'termination_date' => 'required|date',
            'status' => 'required|string|in:Terminated,Resigned,Retired,Deceased,Contract Ended',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,txt|max:4096',
            'reference' => 'nullable|string|max:255',
        ]);

        $identifier = $request->input('identifier');

        // First try to search by employee ID
        $employee = Employee::where('employee_id', $identifier)->first();

        // If not found, try search by ssn_num
        if (!$employee) {
            $employee = Employee::where('ssn_num', $identifier)->first();
        }

        if (!$employee) {
            return redirect()->back()->withErrors(['identifier' => 'Employee not found.']);
        }

        // Proceed update
        $employee->termination_date = $request->input('termination_date');
        $employee->status = $request->input('status');
        $employee->save();

        // File upload as usual...
        if ($request->hasFile('file') && $request->filled('reference')) {
            $firstName = preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));
            $folderName = substr($employee->ssn_num, -7) . '-' . $firstName;
            $folderPath = public_path("emp/" . $folderName);

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $file->move($folderPath, $originalFileName);

            DB::table('files')->insert([
                'employee_id' => $employee->id,
                'file_name' => $originalFileName,
                'description' => $request->input('reference'),
                'uploaded_at' => now(),
            ]);
        }

        return redirect()->route('terminate.form')->with('success', 'Employee status updated successfully.');
    }

    public function uploadFile(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,txt|max:4096',
            'reference' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Get the ssn_num from the hidden input field
        $ssnNum = $request->input('ssn_num');
        $employee = Employee::where('ssn_num', $ssnNum)->first();

        if (!$employee) {
            return response()->json(['error' => 'Employee not found.'], 404);
        }

        // Create the folder name
        $firstName = preg_replace('/[^A-Za-z0-9-_]/', '', str_replace(' ', '_', $employee->first_name));
        $folderName = substr($ssnNum, -7) . '-' . $firstName;
        $folderPath = public_path("emp/" . $folderName);

        // Ensure the folder exists
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $reference = $request->input('reference');
        $file = $request->file('file');

        // Save the original file name in the database
        $originalFileName = $file->getClientOriginalName();

        // Move the file to the folder with its original name
        $file->move($folderPath, $originalFileName);

        // Log the insert values for debugging
        Log::info('Inserting file record:', [
            'employee_id' => $employee->id,
            'file_name' => $originalFileName,  // Save the original file name here
            'description' => $reference,
            'uploaded_at' => now(),
        ]);

        // Insert into the files table using employee's id
        DB::table('files')->insert([
            'employee_id' => $employee->id, // This refers to the primary key in the employees table
            'file_name' => $originalFileName, // Store the original file name
            'description' => $reference,  // Save the reference as the description
            'uploaded_at' => now(),
        ]);

        // Extract last four digits and employment status for redirect
        $lastSixDigits = substr($ssnNum, -7); // Get the last 4 digits of ssn_num
        $employmentStatus = $employee->employment_status; // Assume you can access this from the employee object

        // Return success response and redirect to the employee details page
        return redirect()->route('employees.show', [
            'lastSixDigits' => $lastSixDigits,
            'employmentStatus' => $employmentStatus,
            'firstName' => str_replace(' ', '_', $employee->first_name)
        ])->with('success', 'File uploaded successfully!');
    }
    public function unassignAssetFromEmployee(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:company_assets,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $asset = \App\Models\Asset::findOrFail($validated['asset_id']);
        $employee = \App\Models\Employee::findOrFail($validated['employee_id']);

        DB::transaction(function () use ($asset, $employee) {
            $assignment = \App\Models\AssetAssignment::where('asset_id', $asset->id)
                ->where('employee_id', $employee->id)
                ->first();

            if ($assignment) {
                \App\Models\AssetAssignmentHistory::create([
                    'asset_id' => $asset->id,
                    'employee_id' => $employee->id,
                    'assigned_at' => $assignment->assigned_at,
                    'returned_at' => now(),
                    'remarks' => 'Unassigned from employee details',
                ]);

                $assignment->delete();
            }
        });

        return redirect()->route('employees.show', [
            'lastSixDigits' => substr($employee->ssn_num, -7),
            'employmentStatus' => $employee->employment_status,
            'firstName' => str_replace(' ', '_', $employee->first_name)
        ])->with([
            'success' => 'Asset unassigned successfully!'
        ]);
    }
    public function assignAssetToEmployee(Request $request)
    {
        $validated = $request->validate([
            'asset_db_id' => 'required|exists:company_assets,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $asset = \App\Models\Asset::findOrFail($validated['asset_db_id']);

        DB::transaction(function () use ($asset, $validated) {
            $assignment = \App\Models\AssetAssignment::create([
                'asset_id' => $asset->id,
                'employee_id' => $validated['employee_id'],
                'assigned_at' => now()
            ]);

            \App\Models\AssetAssignmentHistory::create([
                'asset_id' => $asset->id,
                'employee_id' => $validated['employee_id'],
                'assigned_at' => $assignment->assigned_at,
            ]);
        });

        $employee = \App\Models\Employee::findOrFail($validated['employee_id']);

        return redirect()->route('employees.show', [
            'lastSixDigits' => substr($employee->ssn_num, -7),
            'employmentStatus' => $employee->employment_status,
            'firstName' => str_replace(' ', '_', $employee->first_name),
        ])->with('success', 'Asset assigned successfully.');
    }
}
