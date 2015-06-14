<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTableAddFkAgeLevel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Update events, add a relationship to event_age_level
		Schema::table('events', function(Blueprint $table)
		{
			$table->integer("age_level_id")->unsigned()->default(1);
			$table->foreign('age_level_id')->references('id')->on('event_age_level');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->dropColumn('age_level_id');
			$table->dropForeign('events_age_level_id_foreign');
		});
	}

}
