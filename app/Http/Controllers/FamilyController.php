<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Employee;

class FamilyController extends Controller
{
    public function index()
    {
        // Fetch all families and employees
        $families = Family::all();
        $employees = Employee::all(); // Get all employees to display their names in the dropdown

        return view('family', compact('families', 'employees'));
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'employee_ssn' => 'required|exists:employees,ssn_num',
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
        ]);

        $employee = Employee::where('ssn_num', $request->employee_ssn)->firstOrFail();

        $family = Family::where('ssn_num', $employee->ssn_num)->first();

        if ($family) {
            // UPDATE EXISTING
            $family->update([
                'spouse_name' => $request->spouse_name,
                'spouse_status' => $request->spouse_status,
                'spouse_ic' => $request->spouse_ic,
                'spouse_tax' => $request->spouse_tax,
                'noc_under' => $request->noc_under,
                'tax_under' => $request->tax_under,
                'noc_above' => $request->noc_above,
                'tax_above' => $request->tax_above,
                'child1' => $request->child1,
                'child2' => $request->child2,
                'child3' => $request->child3,
                'child4' => $request->child4,
                'child5' => $request->child5,
                'child6' => $request->child6,
                'child7' => $request->child7,
                'child8' => $request->child8,
                'child9' => $request->child9,
                'child10' => $request->child10,
                'contact1_name' => $request->contact1_name,
                'contact1_no' => $request->contact1_no,
                'contact1_rel' => $request->contact1_rel,
                'contact1_add' => $request->contact1_add,
                'contact2_name' => $request->contact2_name,
                'contact2_no' => $request->contact2_no,
                'contact2_rel' => $request->contact2_rel,
                'contact2_add' => $request->contact2_add,
                'contact3_name' => $request->contact3_name,
                'contact3_no' => $request->contact3_no,
                'contact3_rel' => $request->contact3_rel,
                'contact3_add' => $request->contact3_add,
            ]);
        } else {
            // CREATE NEW
            $family = Family::create([
                'name' => $employee->first_name,
                'ssn_num' => $employee->ssn_num,
                'spouse_name' => $request->spouse_name,
                'spouse_status' => $request->spouse_status,
                'spouse_ic' => $request->spouse_ic,
                'spouse_tax' => $request->spouse_tax,
                'noc_under' => $request->noc_under,
                'tax_under' => $request->tax_under,
                'noc_above' => $request->noc_above,
                'tax_above' => $request->tax_above,
                'child1' => $request->child1,
                'child2' => $request->child2,
                'child3' => $request->child3,
                'child4' => $request->child4,
                'child5' => $request->child5,
                'child6' => $request->child6,
                'child7' => $request->child7,
                'child8' => $request->child8,
                'child9' => $request->child9,
                'child10' => $request->child10,
                'contact1_name' => $request->contact1_name,
                'contact1_no' => $request->contact1_no,
                'contact1_rel' => $request->contact1_rel,
                'contact1_add' => $request->contact1_add,
                'contact2_name' => $request->contact2_name,
                'contact2_no' => $request->contact2_no,
                'contact2_rel' => $request->contact2_rel,
                'contact2_add' => $request->contact2_add,
                'contact3_name' => $request->contact3_name,
                'contact3_no' => $request->contact3_no,
                'contact3_rel' => $request->contact3_rel,
                'contact3_add' => $request->contact3_add,
            ]);
        }

        // Update employee with family id if not set
        if (!$employee->family) {
            $employee->family = $family->id;
            $employee->save();
        }

        return redirect()->back()->with('success', 'Family info saved/updated successfully!');
    }
}
