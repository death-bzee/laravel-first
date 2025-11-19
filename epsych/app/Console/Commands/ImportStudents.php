<?php

namespace App\Console\Commands;

use App\Imports\StudentsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportStudents extends Command
{
    protected $signature = 'import:students {file?}';
    protected $description = 'Import students from an Excel file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
         $filePath = $this->argument('file') ?? storage_path('app/public/imports/students.xlsx');

        if (!file_exists($filePath)) {
            $this->error('File not found at: ' . $filePath);
            return 1;
        }

        Excel::import(new StudentsImport, $filePath);

        $this->info('Students imported successfully!');
        return 0;
    }
}
