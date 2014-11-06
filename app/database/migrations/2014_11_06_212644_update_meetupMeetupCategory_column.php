<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMeetupMeetupCategoryColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('meetup_meetupCategories');

		Schema::create('meetup_meetupCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('meetup_id');
			$table->integer('meetupCategories_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
