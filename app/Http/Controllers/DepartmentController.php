<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount([
            'employees as employees_count' => function ($query) {
                $query->whereNull('termination_date');
            }
        ])->paginate(10);

        return view('department.index', compact('departments'));
    }
    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:jobtitles,name',
        ]);

        try {
            Department::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Department added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add department: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('department.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:jobtitles,name,' . $id,
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->only('name'));

        return redirect()->route('department.index')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('department.index')->with('success', 'Department deleted successfully.');
    }

    public function validateField(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:jobtitles,name'],
        ];

        $messages = [
            'name.unique' => 'This department name is already taken.',
            'name.min' => 'The department name must be at least 3 characters.',
        ];

        $validator = Validator::make($request->all(), [
            $request->field => $rules[$request->field] ?? ''
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first($request->field)], 422);
        }

        return response()->json(['success' => true]);
    }

    public function validateEditField(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:jobtitles,name,' . $request->department_id],
        ];

        $validator = Validator::make($request->all(), [
            $request->field => $rules[$request->field]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first($request->field)], 422);
        }

        return response()->json(['success' => true]);
    }

    public function employees($id)
    {
        $department = Department::with([
            'employees' => function ($query) {
                $query->whereNull('termination_date');
            }
        ])->findOrFail($id);

        return view('department.employee', compact('department'));
    }
}
