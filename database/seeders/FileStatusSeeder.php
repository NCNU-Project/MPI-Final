<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class FileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('file_statuses')->insert([
            ['status' => 'queuing'],
            ['status' => 'processing'],
            ['status' => 'done'],
        ]
        );
    }
}
