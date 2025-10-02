<?php

namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount([
            'employees as employees_count' => function ($query) {
                $query->whereNull('termination_date');
            }
        ])->paginate(10);

        return view('company.index', compact('companies'));
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            Company::create([
                'title' => $request->title,
                'description' => $request->description,
                'address' => $request->address,
            ]);

            return redirect()->back()->with('success', 'Company added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add company: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
        ]);

        $company = Company::findOrFail($id);
        $company->update($request->only('title', 'description', 'address'));

        return redirect()->route('company.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
    }

    public function validateField(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'min:5', 'max:255', 'unique:companies,title'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
        ];

        $messages = [
            'title.unique' => 'This title is already taken.',
            'title.min' => 'The title must be at least 5 characters.',
            'address.min' => 'The address must be at least 10 characters.',
        ];

        // Validate only the specific field
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
            'title' => ['required', 'string', 'min:5', 'max:255', 'unique:companies,title,' . $request->company_id],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
        ];

        // Validate only the specific field for edit
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
        $company = Company::with([
            'employees' => function ($query) {
                $query->whereNull('termination_date');
            }
        ])->findOrFail($id);

        return view('company.employee', compact('company'));
    }
}
