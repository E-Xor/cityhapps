<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventsTableNew extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->boolean('watching_count');
			$table->string('olson_path');
			$table->boolean('calendar_count');
			$table->boolean('comment_count');
			$table->string('region_abbr');
			$table->string('postal_code');
			$table->boolean('going_count');
			$table->string('all_day');
			$table->string('latitude');
			$table->boolean('groups');
			$table->string('url');
			$table->string('eventful_id');
			$table->string('privacy');
			$table->string('city_name');
			$table->boolean('link_count');
			$table->string('longitude');
			$table->string('country_name');
			$table->string('country_abbr');
			$table->string('region_name');
			$table->string('start_time');
			$table->boolean('tz_id');
			$table->longText('description');
			$table->string('modified');
			$table->string('venue_display');
			$table->boolean('tz_country');
			$table->boolean('performers');
			$table->string('title');
			$table->string('venue_address');
			$table->string('geocode_type');
			$table->boolean('tz_olson_path');
			$table->boolean('recur_string');
			$table->boolean('calendars');
			$table->string('owner');
			$table->boolean('going');
			$table->string('country_abbr2');
			$table->string('image');
			$table->string('caption');
			$table->string('created');
			$table->string('venue_id');
			$table->boolean('tz_city');
			$table->string('stop_time');
			$table->string('venue_name');
			$table->string('venue_url');
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
		Schema::table('events', function(Blueprint $table)
		{
			//
		});
	}

}
