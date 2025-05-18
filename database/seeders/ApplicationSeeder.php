<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Application;
use App\Models\User;
use App\Models\Job;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'Applicant')->first();
        $job = Job::first();

        if ($user && $job) {
            Application::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'job_id' => $job->id,
                'status' => 'pending',
                'notes' => 'Lamaran awal',
                'applied_at' => now(),
            ]);
        }
    }
}
