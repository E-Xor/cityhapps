<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventbriteEventbriteCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventbrite_eventbriteCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('eventbrite_id');
			$table->integer('eventbriteCategories_id');
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
		Schema::drop('eventbrite_eventbriteCategories');
	}

}
