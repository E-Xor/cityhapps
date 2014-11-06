<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceEventCatgoryTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_activeCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('active_id');
			$table->integer('activeCategories_id');
			$table->timestamps();
		});

		Schema::create('eventful_eventfulCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('eventful_id');
			$table->integer('eventfulCategories_id');
			$table->timestamps();
		});

		Schema::create('eventbrite_eventbriteCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('eventbrite_id');
			$table->integer('eventbriteCategories_id');
			$table->timestamps();
		});

		Schema::create('meetup_meetupCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('active_id');
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
		Schema::drop('active_activeCategories');
		Schema::drop('eventful_eventfulCategories');
		Schema::drop('eventbrite_eventbriteCategories');
		Schema::drop('meetup_meetupCategories');
	}

}
