<?php

namespace App\Jobs;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultiSheetExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $types;
    protected $columns;
    protected $filename;

    public function __construct(array $types, array $columns, string $filename)
    {
        $this->types = $types;
        $this->columns = $columns;
        $this->filename = $filename;
    }

    public function handle()
    {
        $export = new MultiSheetExport($this->types, $this->columns);
        Excel::store($export, $this->filename, 'public'); 
    }
}
