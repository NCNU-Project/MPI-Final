<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FileStatus;
use DB;

class FileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileStatus::firstOrCreate(
            ['status' => 'queuing'],
        );
        FileStatus::firstOrCreate(
            ['status' => 'processing'],
        );
        FileStatus::firstOrCreate(
            ['status' => 'done'],
        );
        FileStatus::firstOrCreate(
            ['status' => 'error'],
        );
    }
}
