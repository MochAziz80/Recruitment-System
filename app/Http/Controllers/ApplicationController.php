<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    // ==== Admin Section ====

    public function adminIndex(Request $request)
    {


        $search = $request->input('search', '');
        $sort = $request->input('sort', 'applied_at');
        $direction = $request->input('direction', 'desc');
        $status = $request->input('status', '');
        $jobId = $request->input('job_id', '');

        $applications = Application::with(['user', 'job'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($jobId, function ($query, $jobId) {
                $query->where('job_id', $jobId);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $jobs = Job::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $applications,
            'jobs' => $jobs,
            'filters' => compact('search', 'sort', 'direction', 'status', 'jobId'),
            'statusOptions' => [
                'pending' => 'Pending',
                'reviewed' => 'Reviewed',
                'rejected' => 'Rejected',
                'accepted' => 'Accepted',
            ],
        ]);
    }

    public function adminCreate()
    {


        $users = User::where('role', 'applicant')->select('id', 'name', 'email')->orderBy('name')->get();
        $jobs = Job::where('is_active', true)->select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/Applications/Create', compact('users', 'jobs'));
    }

    public function adminStore(Request $request)
    {


        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'status' => 'required|in:pending,reviewed,rejected,accepted',
            'notes' => 'nullable|string',
        ]);

        Application::create([
            'user_id' => $validated['user_id'],
            'job_id' => $validated['job_id'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'applied_at' => now(),
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Application created successfully.');
    }

    public function adminEdit(Application $application)
    {


        $application->load(['user', 'job']);

        $users = User::where('role', 'applicant')->select('id', 'name', 'email')->orderBy('name')->get();
        $jobs = Job::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/Applications/Edit', compact('application', 'users', 'jobs'));
    }

    public function adminUpdate(Request $request, Application $application)
    {


        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'status' => 'required|in:pending,reviewed,rejected,accepted',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'user_id' => $validated['user_id'],
            'job_id' => $validated['job_id'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Application updated successfully.');
    }

    public function adminDestroy(Application $application)
    {


        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Application deleted successfully.');
    }

    // ==== User Section ====

    public function myApplications(Request $request)
    {
        $user = $request->user();

        $applications = Application::with('job')
            ->where('user_id', $user->id)
            ->orderBy('applied_at', 'desc')
            ->paginate(10);

        return Inertia::render('Applications/MyApplications', [
            'applications' => $applications,
        ]);
    }

    public function showApplyForm(Request $request, $jobId)
    {


        $job = Job::findOrFail($jobId);
        $user = $request->user();

        return Inertia::render('User/Applications/Apply', compact('job', 'user'));
    }

    public function storeFromUser(Request $request, $jobId)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'cv' => 'required|file|mimes:pdf|max:500',
        ]);

        $job = Job::findOrFail($jobId);
        $user = $request->user();

        $path = $request->file('cv')->store('applications/cv', 'public');

        // Update data user
        $user->update([
            'phone' => $request->phone,
            'cv_path' => $path,
        ]);

        // Simpan aplikasi dengan audit snapshot job
        Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'pending',
            'notes' => null,
            'cv_path' => $path,
            'applied_at' => now(),
            'job_title' => $job->title,               // snapshot judul job
            'job_snapshot' => json_encode($job->toArray()), // snapshot data lengkap job
        ]);

        return redirect()->route('jobs.show', $jobId)->with('success', 'Lamaran berhasil dikirim.');
    }
}
