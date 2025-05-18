<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use APP\Models\User;

class JobController extends Controller
{
    public function index(Request $request)
{
    $jobs = Job::all();
    $user = $request->user(); // ambil user yang sedang login

    $userApplications = [];

    if ($user && $user->role === 'applicant') {
        $userApplications = $user->applications()->pluck('job_id'); // hanya ambil job_id
    }

    return Inertia::render('Jobs/Index', [
        'jobs' => $jobs,
        'userApplications' => $userApplications
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
