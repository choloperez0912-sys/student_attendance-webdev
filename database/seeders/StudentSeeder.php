<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['student_number' => '2024-0001', 'name' => 'Juan Dela Cruz', 'photo' => 'photos/2024-0001.jpg'],
            ['student_number' => '2024-0002', 'name' => 'Maria Santos',   'photo' => 'photos/2024-0002.jpg'],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(['student_number' => $s['student_number']], $s);
        }
    }
}