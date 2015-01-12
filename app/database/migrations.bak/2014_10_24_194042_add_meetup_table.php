<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeetupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetup', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('event_url'); // Event URL
			$table->string('meetupID'); // Event ID (from source)
			$table->string('name'); // Event Name
			$table->string('venueURL'); // Event Venue URL
			$table->string('venueName'); // Event Venue Name
			$table->string('address_1'); // Event Location Address
			$table->string('city'); // Event Location City
			$table->string('state'); // Event Location State
			$table->string('zip'); // Event Location Zip
			$table->string('description'); // Event Description
			$table->string('time'); // Event Start Date/Time
			$table->string('timezone'); // Timezone
			$table->string('duration'); // Event End Date/Time (Duration)
			$table->string('AllDayFlag'); // Event All Day Flag
			$table->string('photo_url'); // Event Image
			$table->string('lat'); // Event Latitude
			$table->string('lon'); // Event Longitude
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
		Schema::drop('meetup');
	}

}
