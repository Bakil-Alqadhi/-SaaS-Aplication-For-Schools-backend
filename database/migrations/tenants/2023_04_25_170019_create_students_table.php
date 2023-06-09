<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('sex');
            $table->date('birthday');
            $table->foreignId('parent_id')
                ->constrained('parent_students')
                ->nullable()
                ->cascadeOnDelete();
            $table->foreignId('grade_id')
                ->constrained('grades')
                ->nullable()
                ->cascadeOnDelete();
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->nullable()
                ->cascadeOnDelete();
            $table->foreignId('section_id')
                ->constrained('sections')
                ->nullable()
                ->cascadeOnDelete();
            $table->string('image');
            $table->boolean('isJoined')->default(false);
            $table->string('userType')->default('student');
            $table->string('address');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('academic_year');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
