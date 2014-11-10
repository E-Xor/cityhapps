<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEventTableColumnsNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 

		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id'); // Event ID (auto)
			$table->string('url')->nullable(); // Event URL
			$table->string('source_id')->nullable(); // Event ID (from source)
			$table->string('event_name')->nullable(); // Event Name
			$table->string('venue_url')->nullable(); // Event Venue URL
			$table->string('venue_name')->nullable(); // Event Venue Name
			$table->string('address')->nullable(); // Event Location Address
			$table->string('city')->nullable(); // Event Location City
			$table->string('state')->nullable(); // Event Location State
			$table->string('zip')->nullable(); // Event Location Zip
			$table->longText('description')->nullable(); // Event Description
			$table->string('start_time')->nullable(); // Event Start Date/Time
			$table->string('end_time')->nullable(); // Event End Date/Time
			$table->string('all_day_flag')->nullable(); // Event All Day Flag
			$table->string('event_image_url')->nullable(); // Event Image
			$table->string('latitude')->nullable(); // Event Latitude
			$table->string('longitude')->nullable(); // Event Longitude
			$table->timestamps();
		});

		Schema::drop('eventful');

		Schema::create('eventful', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('watching_count')->nullable();
			$table->string('olson_path')->nullable();
			$table->boolean('calendar_count')->nullable();
			$table->boolean('comment_count')->nullable();
			$table->string('region_abbr')->nullable();
			$table->string('postal_code')->nullable();
			$table->boolean('going_count')->nullable();
			$table->string('all_day')->nullable();
			$table->string('latitude')->nullable();
			$table->boolean('groups')->nullable();
			$table->string('url')->nullable();
			$table->string('eventful_id')->nullable();
			$table->string('privacy')->nullable();
			$table->string('city_name')->nullable();
			$table->boolean('link_count')->nullable();
			$table->string('longitude')->nullable();
			$table->string('country_name')->nullable();
			$table->string('country_abbr')->nullable();
			$table->string('region_name')->nullable();
			$table->string('start_time')->nullable();
			$table->boolean('tz_id')->nullable();
			$table->longText('description')->nullable();
			$table->string('modified')->nullable();
			$table->string('venue_display')->nullable();
			$table->boolean('tz_country')->nullable();
			$table->boolean('performers')->nullable();
			$table->string('title')->nullable();
			$table->string('venue_address')->nullable();
			$table->string('geocode_type')->nullable();
			$table->boolean('tz_olson_path')->nullable();
			$table->boolean('recur_string')->nullable();
			$table->boolean('calendars')->nullable();
			$table->string('owner')->nullable();
			$table->boolean('going')->nullable();
			$table->string('country_abbr2')->nullable();
			$table->string('image')->nullable();
			$table->string('caption')->nullable();
			$table->string('created')->nullable();
			$table->string('venue_id')->nullable();
			$table->boolean('tz_city')->nullable();
			$table->string('stop_time')->nullable();
			$table->string('venue_name')->nullable();
			$table->string('venue_url')->nullable();
			$table->timestamps();
		});

		Schema::drop('active');

		Schema::create('active', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('urlAdr')->nullable(); // Event URL
			$table->string('assetGuid')->nullable(); // Event ID (from source)
			$table->string('assetName')->nullable(); // Event Name
			$table->string('placeUrlAdr')->nullable(); // Event Venue URL
			$table->string('placeName')->nullable(); // Event Venue Name
			$table->string('addressLine1Txt')->nullable(); // Event Location Address
			$table->string('cityName')->nullable(); // Event Location City
			$table->string('stateProvinceCode')->nullable(); // Event Location State
			$table->string('postalCode')->nullable(); // Event Location Zip
			$table->longText('description')->nullable(); // Event Description
			$table->string('activityStartDate')->nullable(); // Event Start Date/Time
			$table->string('activityEndDate')->nullable(); // Event End Date/Time
			$table->string('AllDayFlag')->nullable(); // Event All Day Flag
			$table->string('imageUrlAdr')->nullable(); // Event Image
			$table->string('lat')->nullable(); // Event Latitude
			$table->string('lon')->nullable(); // Event Longitude
			$table->timestamps();
		});

		Schema::drop('meetup');

		Schema::create('meetup', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('event_url')->nullable(); // Event URL
			$table->string('meetupID')->nullable(); // Event ID (from source)
			$table->string('name')->nullable(); // Event Name
			$table->string('venueURL')->nullable(); // Event Venue URL
			$table->string('venueName')->nullable(); // Event Venue Name
			$table->string('address_1')->nullable(); // Event Location Address
			$table->string('city')->nullable(); // Event Location City
			$table->string('state')->nullable(); // Event Location State
			$table->string('zip')->nullable(); // Event Location Zip
			$table->longText('description')->nullable(); // Event Description
			$table->string('time')->nullable(); // Event Start Date/Time
			$table->string('timezone')->nullable(); // Timezone
			$table->string('duration')->nullable(); // Event End Date/Time (Duration)
			$table->string('AllDayFlag')->nullable(); // Event All Day Flag
			$table->string('photo_url')->nullable(); // Event Image
			$table->string('lat')->nullable(); // Event Latitude
			$table->string('lon')->nullable(); // Event Longitude
			$table->timestamps();
		});

		Schema::drop('eventbrite');

		Schema::create('eventbrite', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id'); // Event ID (auto)
			$table->string('url')->nullable(); // Event URL
			$table->string('eventbriteID')->nullable(); // Event ID (from source)
			$table->string('name_text')->nullable(); // Event Name
			$table->string('venue_resource_uri')->nullable(); // Event Venue URL
			$table->string('venue_name')->nullable(); // Event Venue Name
			$table->string('address_1')->nullable(); // Event Location Address
			$table->string('city')->nullable(); // Event Location City
			$table->string('region')->nullable(); // Event Location State
			$table->string('postal_code')->nullable(); // Event Location Zip
			$table->longText('description_text')->nullable(); // Event Description
			$table->string('start_local')->nullable(); // Event Start Date/Time
			$table->string('end_local')->nullable(); // Event End Date/Time
			$table->string('AllDayFlag')->nullable(); // Event All Day Flag
			$table->string('logo_url')->nullable(); // Event Image
			$table->string('latitude')->nullable(); // Event Latitude
			$table->string('longitude')->nullable(); // Event Longitude
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
