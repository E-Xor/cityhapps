<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoursquareVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('foursquare_venues', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('foursquareVenueID')->unique();
            $table->string('name')->nullable();
            $table->string('verified')->nullable();
            $table->string('url')->nullable();
            $table->string('rating')->nullable();
            $table->string('ratingColor')->nullable();
            $table->string('ratingSignals')->nullable();
            $table->string('storeId')->nullable();
            $table->string('venuePage_id')->nullable();
            $table->string('categories')->nullable();             // serialized array
            $table->string('stats')->nullable();                  // serialized array
            $table->string('specials')->nullable();               // serialized array
            $table->string('photos')->nullable();                 // serialized array
            $table->string('hereNow')->nullable();                // serialized array
            $table->string('contact_phone')->nullable();
            $table->string('contact_formattedPhone')->nullable();
            $table->string('contact_facebook')->nullable();
            $table->string('contact_facebookUsername')->nullable();
            $table->string('contact_facebookName')->nullable();
            $table->string('contact_twitter')->nullable();
            $table->string('location_address')->nullable();
            $table->string('location_crossStreet')->nullable();
            $table->string('location_lat')->nullable();
            $table->string('location_lng')->nullable();
            $table->string('location_postalCode')->nullable();
            $table->string('location_cc')->nullable();
            $table->string('location_neighborhood')->nullable();
            $table->string('location_city')->nullable();
            $table->string('location_state')->nullable();
            $table->string('location_country')->nullable();
            $table->string('hours_status')->nullable();
            $table->string('hours_isOpenNow')->nullable();
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
		Schema::drop('foursquare_venues');
	}

}
