<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\KpiGoal;
use App\Models\KpiGoalTracking;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\KpiStoreRequest;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Fetch departments for filter dropdown and capture filter parameters
        $departments = Department::all();
        // Use input() to retrieve arrays from checkboxes, defaulting to null if not present
        $filterStatus = $request->get('status');
        $filterDepartment = $request->get('department');

        // 2. Initialize the query
        $query = Kpi::with(['department', 'manager', 'assignedStaff', 'goals']); // Eager load relationships including goals

        // 3. Apply base access filtering
        if (!in_array($user->access, ['Admin', 'HR', 'Manager'])) {
            // For 'Staff' and 'Employee' access, filter down to their own KPIs in specific statuses
            $query->where('assigned_to_staff_id', $user->id)
                ->whereIn('status', ['for review', 'accepted', 'declined']);
        }
        // For Admin/HR/Manager, the query starts wide, allowing them to see everything
        // before the optional filters are applied.

        // 4. Apply optional filters (Status and Department)
        if (!empty($filterStatus) && is_array($filterStatus)) {
            $query->whereIn('status', $filterStatus);
        }

        if (!empty($filterDepartment) && is_array($filterDepartment)) {
            $query->whereIn('department_id', $filterDepartment);
        }

        // 5. Apply default sorting and execute the query
        $kpis = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 6. Pass necessary data to the view
        return view('pms.kpi.index', compact(
            'kpis',
            'departments',
            // Ensure filter variables are always arrays (or null/false if truly empty)
            'filterStatus',
            'filterDepartment'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('pms.kpi.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     * @param KpiStoreRequest $request
     */

    public function store(KpiStoreRequest $request)
    {
        $validatedData = $request->validated(); // <-- Get all validated data

        $goalsData = $validatedData['goals'];
        unset($validatedData['goals']);


        $action = $request->input('action');
        if ($action === 'draft') {
            $status = 'draft';
        } elseif ($action === 'publish') {
            $status = 'template';
        }

        // Calculate total weightage 
        $totalWeightage = array_sum(array_column($goalsData, 'weightage'));

        // Prepare the main data
        $kpiData = array_merge($validatedData, [
            'manager_id' => Auth::id(),
            'total_weightage' => $totalWeightage,
            'status' => $status,
            'year' => date('Y'), // 'year' is needed and defaults to current year
        ]);

        // Create main kpi record
        $kpi = Kpi::create($kpiData);

        // Create the associated KpiGoal records
        // Attach the kpi_id to each goal before saving
        $goalsToInsert = array_map(function ($goal) use ($kpi) {
            return array_merge($goal, ['kpi_id' => $kpi->id]);
        }, $goalsData);

        // Mass insert the goals
        $kpi->goals()->createMany($goalsData);

        return redirect()->route('kpi.index')->with('success', 'KPI created successfully!');
    }

    public function show(Kpi $kpi)
    {
        // Modify the existing query to eager-load the goals and their trackings
        $kpi->load([
            // Load goals, and for each goal, load its trackings (entries)
            'goals' => function ($query) {
                $query->with('trackings');
            }
        ]);

        // You may also want to load the manager and staff if they are not already.
        $kpi->load('manager', 'assignedStaff');

        // The logic below this line should remain the same (returning the view)
        return view('pms.kpi.show', compact('kpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kpi $kpi)
    {
        // Managers can edit drafts and 'for review' KPIs
        if ($kpi->manager_id == Auth::id() && (in_array($kpi->status, ['draft', 'for review', 'declined', 'template']))) {
            $departments = Department::all();
            return view('pms.kpi.edit', compact('kpi', 'departments'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Illuminate\Http\Request $request
     * @param Kpi $kpi
     */

    public function update(KpiStoreRequest $request, Kpi $kpi)
    {
        // Check if the user is the manager who owns this KPI
        if ($kpi->manager_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this KPI.');
        }

        // Get all validated data as an array
        $validatedData = $request->validated();

        // Separate goals data from the main KPI data
        $goalsData = $validatedData['goals'];
        unset($validatedData['goals']);

        // 1. Determine status based on button clicked
        $action = $request->input('action');
        $status = $kpi->status; // Default to retaining the original status

        if ($action === 'publish') {
            $status = 'template';
        } elseif ($action === 'draft') {
            $status = 'draft';
        } elseif ($action === 'review' || $action === 'resubmit') {
            $status = 'for review';
        }

        // 2. Calculate total weightage using the validated data
        $totalWeightage = array_sum(array_column($goalsData, 'weightage'));

        // Prepare main KPI update data
        $kpiUpdateData = array_merge($validatedData, [
            'status' => $status,
            'total_weightage' => $totalWeightage,
            // Ensure manager_id isn't changed if not needed, but keep it available if the original logic required it
        ]);

        // 3. Update the main KPI record
        $kpi->update($kpiUpdateData);

        // 4. Delete all old goals and save new ones (replacement strategy)
        $kpi->goals()->delete();
        $kpi->goals()->createMany($goalsData);

        return redirect()->route('kpi.index')->with('success', 'KPI updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kpi $kpi)
    {
        // Only allow deletion if the KPI is in 'draft' or 'for review' or 'template' status and the user is the manager
        if (($kpi->status !== 'draft' && $kpi->status !== 'for review' && $kpi->status !== 'template') || $kpi->manager_id !== Auth::id()) {
            return redirect()->route('kpi.index')->with('error', 'This KPI cannot be deleted.');
        }

        $kpi->delete();

        return redirect()->route('kpi.index')->with('success', 'KPI deleted successfully.');
    }

    /**
     * Show the form for a manager to assign a KPI to a staff member.
     */
    public function showAssignmentForm(Kpi $kpi)
    {
        // Get the IDs of all staff members who already have this KPI assigned to them
        // Use 'kpi_id_from_template' to find all assigned copies
        $assignedStaffIds = Kpi::where('kpi_id_from_template', $kpi->id)
            ->pluck('assigned_to_staff_id')
            ->toArray();

        // Get employees from the same department and unit, but exclude those who already have this KPI
        $employees = Employee::where('department', $kpi->department_id)
            ->when($kpi->unit, function ($query) use ($kpi) {
                return $query->where('unit', $kpi->unit);
            })
            ->whereNotIn('user_id', $assignedStaffIds) // This line filters out already assigned staff
            ->get();

        return view('pms.kpi.assign', compact('kpi', 'employees'));
    }

    /**
     * Assign a KPI to a staff member.
     */
    public function assign(Request $request, Kpi $kpi)
    {
        // $kpi here is the TEMPLATE KPI

        $request->validate([
            'staff_members' => 'required|array',
            'staff_members.*' => 'exists:users,id',
            // Ensure you are passing an array of weightages keyed by staff ID.
            'weightages' => 'required|array',
        ]);

        $staffMembers = $request->input('staff_members');
        $staffWeightages = $request->input('weightages');

        // 1. Fetch the goals of the template KPI (Eager loaded earlier, but access here)
        $templateGoals = $kpi->goals->toArray();

        // Loop through the selected staff members
        foreach ($staffMembers as $staffId) {
            $staffWeightagesData = $staffWeightages[$staffId];

            // 2. Create the new assigned KPI (cloning core fields)
            $newKpi = $kpi->replicate();
            $newKpi->manager_id = Auth::id(); // Assigning Manager (the current user)
            $newKpi->assigned_to_staff_id = $staffId;
            $newKpi->kpi_id_from_template = $kpi->id;
            $newKpi->status = 'for review';
            // Save the core KPI first to get an ID
            $newKpi->save();

            // Initialize and assemble goal data 
            $goalsToInsert = [];
            $totalWeightage = 0;

            // 3. Loop through the template goals to create new goals and calculate total weightage
            foreach ($templateGoals as $i => $templateGoal) {
                // Get the weightage for this specific staff member and this goal index
                // Note: The index in the request starts at 1, while $i starts at 0.
                $weightageKey = 'weightage_' . ($i + 1);

                // Use the staff's specific weightage from the form, or default to the template's weightage
                $newWeightage = $staffWeightagesData[$weightageKey] ?? $templateGoal['weightage'];

                $totalWeightage += $newWeightage;

                $goalsToInsert[] = [
                    'goal' => $templateGoal['goal'],
                    'measurement' => $templateGoal['measurement'],
                    'weightage' => $newWeightage,
                    // 'kpi_id' will be set automatically by createMany if we use the relationship
                ];
            }

            // 4. Update the main KPI with the final calculated total weightage (ONE DB CALL)
            $newKpi->update(['total_weightage' => $totalWeightage]);

            // 5. Save all new goal records for the newly created KPI (ONE DB CALL)
            $newKpi->goals()->createMany($goalsToInsert);
        }

        return redirect()->route('kpi.index')->with('success', 'KPI assigned to selected staff successfully!');
    }

    public function accept(Kpi $kpi)
    {
        // Only allow staff to accept their own KPIs that are "for review"
        if ($kpi->assigned_to_staff_id !== Auth::id() || $kpi->status !== 'for review') {
            return redirect()->back()->with('error', 'You are not authorized to accept this KPI.');
        }

        $kpi->update([
            'accepted_at' => now(),
            'status' => 'accepted'
        ]);

        return redirect()->back()->with('success', 'KPI has been approved.');
    }

    public function requestRevision(Kpi $kpi)
    {
        // Only allow staff to reject their own KPIs that are "for review"
        if ($kpi->assigned_to_staff_id !== Auth::id() || $kpi->status !== 'for review') {
            return redirect()->back()->with('error', 'You are not authorized to request revision for this KPI.');
        }

        $kpi->update([
            'status' => 'declined'
        ]);

        return redirect()->back()->with('success', 'KPI revision has been requested.');
    }

    public function trackGoal(Request $request, KpiGoal $kpiGoal)
    {
        $user = Auth::user();
        $isManager = in_array($user->access, ['Admin', 'HR', 'Manager']);
        $staffId = $kpiGoal->kpi->assigned_to_staff_id;

        // Authorization Check (remains the same)
        if ((!$isManager && $staffId !== $user->id) || ($isManager && $kpiGoal->kpi->manager_id !== $user->id && !in_array($user->access, ['Admin', 'HR']))) {
            return redirect()->back()->with('error', 'You are not authorized to modify tracking for this goal.');
        }

        $updateData = [];
        $message = 'Tracking updated successfully.';

        // Staff is updating achievement
        if (!$isManager && $request->filled('achievement')) {
            $updateData['achievement'] = $request->input('achievement');
        }

        // Manager is adding/updating comment
        else if ($isManager && $request->filled('manager_comment')) {
            $updateData['manager_comment'] = $request->input('manager_comment');
        }

        if (empty($updateData)) {
            return redirect()->back()->with('error', 'No data submitted to update.');
        }

        // Use updateOrCreate to ensure a single record for this goal/staff member
        KpiGoalTracking::updateOrCreate(
            [
                'kpi_goal_id' => $kpiGoal->id,
                'user_id' => $staffId, // Always key to the assigned staff member
            ],
            $updateData // Update only the relevant field(s)
        );

        return redirect()->back()->with('success', $message);
    }
}
