<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ParentStudent;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // DB::setDefaultConnection('tenant');
        // DB::beginTransaction();
        // // Specialization::factory(5)->create();
        // Teacher::factory(10)->create();
        // DB::commit();
        // DB::setDefaultConnection('mysql');

        // ParentStudent::factory(5)->create();
        Student::factory(20)->create();
    }
}
