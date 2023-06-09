<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->bigInteger('grade_id')->unsigned();
            $table->foreign('grade_id')->references('id')->on('Grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('classrooms');
    }
};
/**
$table->id();
            $table->string('name');
            $table->string('status');
            $table->bigInteger('grade_id')->unsigned();
            $table->foreign('grade_id')->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
*/
