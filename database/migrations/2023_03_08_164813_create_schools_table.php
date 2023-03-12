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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->unique();
            $table->string('domain')->unique();
            $table->json('database_options');
            $table->string( 'address')->unique();
            $table->string('phone')->unique();
            $table->string('school_image');
            $table->string('director_image');
            $table->text('about_school');
            $table->text('about_director');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
