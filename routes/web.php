<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ApplicantController as AdminApplicantController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


Route::get('/', function () {
    return Inertia::render('Landing');
});

Route::get('/preview-cv/{filename}', function ($filename) {
    $path = public_path("storage/applications/cv/{$filename}");

    if (!file_exists($path)) {
        abort(404, "File not found");
    }

    return Response::stream(function () use ($path) {
        readfile($path);
    }, 200, [
        'Content-Type' => mime_content_type($path),
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        'Content-Length' => filesize($path),
    ]);
})->name('preview.cv');



Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');


Route::middleware(['auth'])->group(function () {

    Route::middleware('role:administrator')->get('admin/dashboard', function () {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users' => User::count(),
                'jobs' => Job::count(),
                'applications' => Application::count(),
            ],
            'user' => Auth::user(),
        ]);
    })->name('admin.dashboard');

    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.mine');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/jobs/{job}/apply', [ApplicationController::class, 'showApplyForm'])->name('applications.applyForm');
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'storeFromUser'])->name('applications.storeFromUser');

    Route::middleware(['role:administrator'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('jobs', AdminJobController::class)->except(['index', 'show']);
        Route::resource('applications', ApplicationController::class);
    });

    Route::middleware(['role:administrator'])->prefix('admin')->group(function () {
        Route::get('/user', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/user/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/user/generate', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('/user/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/user/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });



    Route::prefix('admin')->middleware(['role:administrator'])->name('admin.')->group(function () {
        Route::resource('jobs', AdminJobController::class);
    });

    Route::prefix('admin')->middleware(['role:administrator'])->name('admin.')->group(function () {
        Route::resource('applicants', AdminApplicantController::class)
            ->only(['index', 'show']);
        Route::put('/applicants/{id}/status', [AdminApplicantController::class, 'updateStatus'])->name('admin.applicants.updateStatus');
    });

    Route::prefix('admin')->middleware(['role:administrator'])->name('admin.')->group(function () {

        Route::get('/import', fn() => Inertia::render('Admin/Import'))->name('import.view');
        Route::get('/export', fn() => Inertia::render('Admin/Export'))->name('export.view');


        Route::post('/export', [ExportController::class, 'export'])->name('admin.export');
        Route::get('/admin/export/status/{filename}', [ExportController::class, 'checkExportStatus']);
    });
});

require __DIR__ . '/auth.php';
