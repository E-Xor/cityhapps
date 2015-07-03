<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventfulVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventful_venues', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('eventfulVenueID');
            $table->string('geocode_type')->nullable();
            $table->string('event_count')->nullable();
            $table->string('trackback_count')->nullable();
            $table->string('comment_count')->nullable();
            $table->string('region_abbr')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('url')->nullable();
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->string('owner')->nullable();
            $table->string('link_count')->nullable();
            $table->string('country_name')->nullable();
            $table->string('longitude')->nullable();
            $table->string('timezone')->nullable();
            $table->string('country_abbr')->nullable();
            $table->string('region_name')->nullable();
            $table->string('country_abbr2')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('created')->nullable();
            $table->string('venue_type')->nullable();
//            $table->string('venue_name')->nullable();
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
		Schema::drop('eventful_venues');
	}

}
