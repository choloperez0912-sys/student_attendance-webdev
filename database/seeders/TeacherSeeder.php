<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // The User model hashes the password automatically.
        // CHANGE THESE after your first login.
        User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Teacher', 'password' => 'password123']
        );
    }
}