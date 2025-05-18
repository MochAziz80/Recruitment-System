<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ExportDataJob;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'types' => 'required|array',
            'columns' => 'required|array',
        ]);

        $types = $request->input('types');
        $columns = $request->input('columns');
        $filename = 'export_'.date('Ymd_His').'.xlsx';

        ExportDataJob::dispatch($types, $columns, $filename);

        return response()->json([
            'message' => 'Export sedang diproses, file akan tersedia setelah selesai.',
            'download_url' => Storage::url($filename),
        ]);
    }
}
