<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class JobController extends Controller
{
    public function index(Request $request)
{
    $query = Job::with('postedBy');

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    $jobs = $query->get();

    return Inertia::render('Admin/Jobs/Index', [
        'jobs' => $jobs,
        'filters' => $request->only(['search', 'is_active']),
    ]);
}

    public function create()
    {
        return Inertia::render('Admin/Jobs/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'requirements' => 'nullable|array',
            'is_active' => 'boolean',
            'posted_at' => 'nullable|date',
        ]);

        $validated['uploaded_by'] = Auth::id();

        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully');
    }

    public function edit($id)
    {
        $job = Job::with('audits')->findOrFail($id);
        return Inertia::render('Admin/Jobs/Edit', [
            'job' => $job,
            'audits' => $job->audits()->latest()->get(),
        ]);
    }


    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted');
    }
}
