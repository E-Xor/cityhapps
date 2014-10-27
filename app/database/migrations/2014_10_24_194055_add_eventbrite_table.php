<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventbriteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventbrite', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('url'); // Event URL
			$table->string('eventbriteID'); // Event ID (from source)
			$table->string('name_text'); // Event Name
			$table->string('venue_resource_uri'); // Event Venue URL
			$table->string('venue_name'); // Event Venue Name
			$table->string('address_1'); // Event Location Address
			$table->string('city'); // Event Location City
			$table->string('region'); // Event Location State
			$table->string('postal_code'); // Event Location Zip
			$table->string('description_text'); // Event Description
			$table->string('start_local'); // Event Start Date/Time
			$table->string('end_local'); // Event End Date/Time
			$table->string('AllDayFlag'); // Event All Day Flag
			$table->string('logo_url'); // Event Image
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
		Schema::drop('eventbrite');
	}

}
