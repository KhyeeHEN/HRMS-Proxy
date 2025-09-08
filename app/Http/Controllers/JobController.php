<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    // Display job list with pagination
    public function index(Request $request)
    {
        $jobs = Job::paginate($request->get('per_page', 10));
        return view('jobs.index', compact('jobs'));
    }

    // Store a new job
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:0',
            'applicants' => 'nullable|integer|min:0',
            'interviewed' => 'nullable|integer|min:0',
            'hired' => 'nullable|integer|min:0',
        ]);

        Job::create([
            'title' => $request->title,
            'vacancies' => $request->vacancies,
            'applicants' => $request->applicants ?? 0,
            'interviewed' => $request->interviewed ?? 0,
            'hired' => $request->hired ?? 0,
        ]);

        return redirect()->route('job.index')->with('success', 'Job added successfully!');
    }

    // Edit job form
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:0',
            'applicants' => 'required|integer|min:0',
            'interviewed' => 'required|integer|min:0',
            'hired' => 'required|integer|min:0',
        ]);

        $job = Job::findOrFail($id);
        $job->update($request->only(['title', 'vacancies', 'applicants', 'interviewed', 'hired']));

        // Instead of redirecting to index, return to edit view with the updated job and success message
        return redirect()->route('jobs.edit', $job->id)
            ->with('success', 'Job updated successfully!');
    }
    // Delete job
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully!');
    }
}
