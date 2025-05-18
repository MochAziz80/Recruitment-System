<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $lastWeek = $now->copy()->subWeek();

        // Total counts
        $totalUsers = User::count();
        $totalJobs = Job::count();
        $totalApplications = Application::count();

        // Count last week for comparison
        $usersLastWeek = User::where('created_at', '>=', $lastWeek)->count();
        $jobsLastWeek = Job::where('created_at', '>=', $lastWeek)->count();
        $applicationsLastWeek = Application::where('created_at', '>=', $lastWeek)->count();

        // Calculate % change vs last week (simple version)
        $usersChange = $this->percentChange($totalUsers, $usersLastWeek);
        $jobsChange = $this->percentChange($totalJobs, $jobsLastWeek);
        $applicationsChange = $this->percentChange($totalApplications, $applicationsLastWeek);

        // Recent activities: ambil 10 terbaru gabungan dari user, job, application (misal berdasarkan created_at)
        // Contoh sederhana, bisa buat query union atau ambil data terpisah dan gabungkan di PHP
        $recentUser = User::latest()->take(3)->get()->map(fn($u) => [
            'id' => $u->id,
            'type' => 'user',
            'message' => "New user registered",
            'time' => $u->created_at->diffForHumans(),
            'status' => 'success',
        ]);

        $recentJob = Job::latest()->take(3)->get()->map(fn($j) => [
            'id' => $j->id,
            'type' => 'job',
            'message' => "New job posted",
            'time' => $j->created_at->diffForHumans(),
            'status' => 'success',
        ]);

        $recentApplication = Application::latest()->take(4)->get()->map(fn($a) => [
            'id' => $a->id,
            'type' => 'application',
            'message' => "New application submitted",
            'time' => $a->created_at->diffForHumans(),
            'status' => $a->status === 'pending' ? 'pending' : 'success',
        ]);

        $recentActivity = $recentUser->concat($recentJob)->concat($recentApplication)
            ->sortByDesc('time')
            ->take(10)
            ->values();

        // Notifications (asumsikan ada tabel notifications dengan kolom message, read, created_at)
        // $notifications = Notification::latest()->take(5)->get()->map(fn($n) => [
        //     'id' => $n->id,
        //     'message' => $n->message,
        //     'time' => $n->created_at->diffForHumans(),
        //     'read' => $n->read,
        // ]);
        $notifications = [];
        // Data stats yang dikirim ke frontend
        $stats = [
            'users' => $totalUsers,
            'jobs' => $totalJobs,
            'applications' => $totalApplications,
        ];

        $comparisonStats = [
            'users' => [
                'change' => $usersChange,
                'isIncrease' => $usersChange >= 0,
            ],
            'jobs' => [
                'change' => $jobsChange,
                'isIncrease' => $jobsChange >= 0,
            ],
            'applications' => [
                'change' => $applicationsChange,
                'isIncrease' => $applicationsChange >= 0,
            ],
        ];

        return inertia('Admin/Dashboard', compact('stats', 'comparisonStats', 'recentActivity', 'notifications'));
    }

    // Helper function menghitung persentase perubahan
    private function percentChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
