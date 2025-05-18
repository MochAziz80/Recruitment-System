<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class MultiSheetExport implements WithMultipleSheets
{
    protected $types;
    protected $columns;

    public function __construct(array $types, array $columns)
    {
        $this->types = $types;
        $this->columns = $columns;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->types as $type) {
            $data = collect();
            $title = '';

            switch ($type) {
                case 'users':
                    $data = User::select($this->columns[$type])->get();
                    $title = 'Users';
                    break;
                case 'jobs':
                    $data = Job::select($this->columns[$type])->get();
                    $title = 'Jobs';
                    break;
                case 'applications':
                    $data = Application::select($this->columns[$type])->get();
                    $title = 'Applications';
                    break;
            }

            $sheets[] = new class($data, $title) implements FromCollection, WithTitle {
                private $data;
                private $title;

                public function __construct($data, $title)
                {
                    $this->data = $data;
                    $this->title = $title;
                }

                public function collection()
                {
                    return $this->data;
                }

                public function title(): string
                {
                    return $this->title;
                }
            };
        }

        return $sheets;
    }
}
