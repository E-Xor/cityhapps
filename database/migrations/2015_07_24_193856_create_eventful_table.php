<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventfulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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
			$table->text('description')->nullable();
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
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eventful');
	}

}
