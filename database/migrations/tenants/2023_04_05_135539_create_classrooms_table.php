<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

	public function up()
	{
		Schema::create('classrooms', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name_classroom', 225);
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