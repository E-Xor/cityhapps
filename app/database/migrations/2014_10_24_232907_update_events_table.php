<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('events');

		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id'); // Event ID (auto)
			$table->string('url'); // Event URL
			$table->string('source_id'); // Event ID (from source)
			$table->string('event_name'); // Event Name
			$table->string('venue_url'); // Event Venue URL
			$table->string('venue_name'); // Event Venue Name
			$table->string('address'); // Event Location Address
			$table->string('city'); // Event Location City
			$table->string('state'); // Event Location State
			$table->string('zip'); // Event Location Zip
			$table->string('description'); // Event Description
			$table->string('start_time'); // Event Start Date/Time
			$table->string('end_time'); // Event End Date/Time
			$table->string('all_day_flag'); // Event All Day Flag
			$table->string('event_image_url'); // Event Image
			$table->string('latitude'); // Event Latitude
			$table->string('longitude'); // Event Longitude
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
		Schema::drop('events');
	}

}
