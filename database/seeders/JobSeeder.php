<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Job;

class JobSeeder extends Seeder
{
    public function run()
    {
        Job::factory()->count(10)->create();
    }
}
