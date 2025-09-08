<?php

// app/Http/Controllers/AssetController.php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\AssetAssignment;
use App\Models\AssetAssignmentHistory;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AssetController extends Controller
{
    /**
     * Display a listing of the assets.
     */
    public function index()
    {
        $assets = Asset::with(['currentAssignment.employee', 'departmentInfo', 'category'])->get();
        $employees = Employee::where('status', 'Active')->get();
        $departments = JobTitle::all(); // Fetch departments (job titles)
        $categories = AssetCategory::all();

        // Count totals
        $totalAssets = Asset::count();
        $assignedAssets = AssetAssignment::count();
        $unassignedAssets = $totalAssets - $assignedAssets;

        return view('assets.index', compact('assets', 'employees', 'departments', 'categories', 'totalAssets', 'assignedAssets', 'unassignedAssets'));
    }

    /**
     * Store a newly created asset in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'nullable|string|max:255',
            'asset_name' => 'required|string|max:255',
            'department' => 'nullable|integer|exists:jobtitles,id',
            'type' => 'required|exists:asset_categories,id',
            'status' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:255',
            'sn_no' => 'nullable|string|max:100',
            'cpu' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'hdd_bal' => 'nullable|string|max:100',
            'hdd2' => 'nullable|string|max:100',
            'hdd2_bal' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'ssd_bal' => 'nullable|string|max:100',
            'os' => 'nullable|string|max:100',
            'os_key' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:100',
            'office_key' => 'nullable|string|max:255',
            'office_login' => 'nullable|string|max:255',
            'antivirus' => 'nullable|string|max:255',
            'synology' => 'nullable|string|max:255',
            'dop' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
            'warranty_end' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $employeeId = $validated['employee_id'] ?? null;
        unset($validated['employee_id']);

        $asset = Asset::create($validated);

        if ($employeeId) {
            AssetAssignment::create([
                'asset_id' => $asset->id,
                'employee_id' => $employeeId,
            ]);

            AssetAssignmentHistory::create([
                'asset_id' => $asset->id,
                'employee_id' => $employeeId,
                'assigned_at' => now(),
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Asset successfully added.');
    }
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $employees = Employee::where('status', 'Active')->get();
        $departments = JobTitle::all();
        $categories = AssetCategory::all();

        return view('assets.edit', compact('asset', 'employees', 'departments', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'asset_id' => 'nullable|string|max:255',
            'asset_name' => 'required|string|max:255',
            'user' => 'nullable|string|max:255',
            'department' => 'nullable|integer|exists:jobtitles,id',
            'type' => 'required|exists:asset_categories,id',
            'status' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:255',
            'sn_no' => 'nullable|string|max:100',
            'cpu' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:100',
            'hdd' => 'nullable|string|max:100',
            'hdd_bal' => 'nullable|string|max:100',
            'hdd2' => 'nullable|string|max:100',
            'hdd2_bal' => 'nullable|string|max:100',
            'ssd' => 'nullable|string|max:100',
            'ssd_bal' => 'nullable|string|max:100',
            'os' => 'nullable|string|max:100',
            'os_key' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:100',
            'office_key' => 'nullable|string|max:255',
            'office_login' => 'nullable|string|max:255',
            'antivirus' => 'nullable|string|max:255',
            'synology' => 'nullable|string|max:255',
            'dop' => 'nullable|digits:4',
            'warranty_end' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $employeeId = $validated['employee_id'] ?? null;
        unset($validated['employee_id']);

        DB::transaction(function () use ($id, $validated, $employeeId) {
            $asset = Asset::findOrFail($id);
            $asset->update($validated);

            $assignment = AssetAssignment::where('asset_id', $asset->id)->first();

            $currentAssignedId = $assignment?->employee_id;

            // If employee has changed
            if ($currentAssignedId !== $employeeId) {
                // Record previous assignment in history
                if ($assignment && $assignment->employee_id) {
                    AssetAssignmentHistory::create([
                        'asset_id' => $asset->id,
                        'employee_id' => $assignment->employee_id,
                        'assigned_at' => $assignment->assigned_at,
                        'returned_at' => now(),
                        'remarks' => 'Auto-logged on reassignment',
                    ]);
                }

                // Update or delete the current assignment
                if ($employeeId) {
                    // If new employee exists, update or create assignment
                    AssetAssignment::updateOrCreate(
                        ['asset_id' => $asset->id],
                        ['employee_id' => $employeeId, 'assigned_at' => now()]
                    );
                } else {
                    // Unassign asset if employee_id is null
                    if ($assignment) {
                        $assignment->delete();
                    }
                }
            }
        });

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json(['success' => true, 'message' => 'Asset deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting asset.'], 500);
        }
    }
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'asset_db_id' => 'required|exists:company_assets,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $asset = Asset::findOrFail($validated['asset_db_id']);

        DB::transaction(function () use ($asset, $validated) {
            $assignment = AssetAssignment::updateOrCreate(
                ['asset_id' => $asset->id],
                ['employee_id' => $validated['employee_id'], 'assigned_at' => now()]
            );

            AssetAssignmentHistory::create([
                'asset_id' => $asset->id,
                'employee_id' => $validated['employee_id'],
                'assigned_at' => $assignment->assigned_at,
            ]);
        });

        $employee = Employee::with('departmentName')->findOrFail($validated['employee_id']);

        return response()->json([
            'success' => true,
            'employee' => [
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'department' => optional($employee->departmentName)->name,
                'job_title' => $employee->job_title,
                'mobile_phone' => $employee->mobile_phone,
                'work_email' => $employee->work_email,
            ]
        ]);
    }

    /**
     * Unassign an employee from an asset.
     */
    public function unassign(Request $request)
    {
        $validated = $request->validate([
            'asset_db_id' => 'required|exists:company_assets,id',
        ]);

        $asset = Asset::findOrFail($validated['asset_db_id']);

        DB::transaction(function () use ($asset) {
            $assignment = AssetAssignment::where('asset_id', $asset->id)->first();

            if ($assignment && $assignment->employee_id) {
                // Record the assignment in history before deletion
                AssetAssignmentHistory::create([
                    'asset_id' => $asset->id,
                    'employee_id' => $assignment->employee_id,
                    'assigned_at' => $assignment->assigned_at,
                    'returned_at' => now(),
                    'remarks' => 'Auto-logged on unassignment',
                ]);

                // Delete the current assignment
                $assignment->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Assignee removed successfully.'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $employeeId = $request->input('employee_id');

        $assets = Asset::with(['currentAssignment.employee', 'departmentInfo', 'category'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('asset_name', 'like', "%{$query}%")
                        ->orWhere('model', 'like', "%{$query}%")
                        ->orWhere('sn_no', 'like', "%{$query}%");
                });
            })
            ->when($employeeId, function ($q) use ($employeeId) {
                $q->whereHas('currentAssignment', function ($subQuery) use ($employeeId) {
                    $subQuery->where('employee_id', $employeeId);
                });
            })
            ->get();

        $employees = Employee::where('status', 'Active')->get();
        $departments = JobTitle::all();
        $categories = AssetCategory::all();

        $totalAssets = Asset::count();
        $assignedAssets = AssetAssignment::count();
        $unassignedAssets = $totalAssets - $assignedAssets;

        return view('assets.index', compact(
            'assets',
            'employees',
            'departments',
            'categories',
            'totalAssets',
            'assignedAssets',
            'unassignedAssets'
        ));
    }
    public function filter(Request $request)
    {
        $filters = $request->input('filters', []);

        $query = Asset::with(['currentAssignment.employee', 'departmentInfo', 'category']);

        if (!empty($filters['category'])) {
            $query->whereIn('type', $filters['category']);
        }

        if (!empty($filters['department'])) {
            $query->whereIn('department', $filters['department']);
        }

        //add filter for assigned and unassigned 
        if (!empty($filters['status'])) {
            $query->whereIn('status', $filters['status']);
        }


        $assets = $query->get();

        // Existing shared data
        $employees = Employee::where('status', 'Active')->get();
        $departments = JobTitle::all();
        $categories = AssetCategory::all();
        $totalAssets = Asset::count();
        $assignedAssets = AssetAssignment::count();
        $unassignedAssets = $totalAssets - $assignedAssets;

        return view('assets.index', compact(
            'assets',
            'employees',
            'departments',
            'categories',
            'totalAssets',
            'assignedAssets',
            'unassignedAssets'
        ));
    }
}
