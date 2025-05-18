<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user', 'job']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('applied_at', 'desc')->get();

        return Inertia::render('Admin/Applicants/Index', [
            'applications' => $applications,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show($id)
    {
        $application = Application::with(['user', 'job'])->findOrFail($id);

        return Inertia::render('Admin/Applicants/Show', [
            'application' => $application,
        ]);
    }

    public function previewCV($filename)
    {
        $filename = basename($filename);
        $path = "public/applications/cv/{$filename}";

        if (!Storage::exists($path)) {
            abort(404, "File not found");
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

     public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        $application = Application::findOrFail($id);
        $application->status = $request->status;
        $application->notes = $request->notes;
        $application->save();

        return redirect()->back()->with('success', 'Status application updated.');
    }

    // Tambah method update jika ingin approve/reject aplikasi dsb
}
