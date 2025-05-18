<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
        ]);
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'is_active'=>'boolean',
        ]);

        Job::create($request->all());

        return redirect()->route('jobs.index')->with('success', 'Job created');
    }

    public function show(Request $request, $id)
{
    $job = Job::findOrFail($id);
    $user = $request->user();

    $hasApplied = false;
    if ($user && $user->role === 'applicant') {
        $hasApplied = Application::where('user_id', $user->id)
                                ->where('job_id', $job->id)
                                ->exists();
    }

    return Inertia::render('Jobs/Show', [
        'job' => $job,
        'hasApplied' => $hasApplied,
    ]);
}
}
