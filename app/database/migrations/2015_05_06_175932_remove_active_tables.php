<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveActiveTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('active');
		Schema::drop('activeCategories');
		Schema::drop('active_activeCategories');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('active', function(Blueprint $table)
		{
			$table->increments('id'); // Event ID (auto)
			$table->string('urlAdr'); // Event URL
			$table->string('assetGuid'); // Event ID (from source)
			$table->string('assetName'); // Event Name
			$table->string('placeUrlAdr'); // Event Venue URL
			$table->string('placeName'); // Event Venue Name
			$table->string('addressLine1Txt'); // Event Location Address
			$table->string('cityName'); // Event Location City
			$table->string('stateProvinceCode'); // Event Location State
			$table->string('postalCode'); // Event Location Zip
			$table->string('description'); // Event Description
			$table->string('activityStartDate'); // Event Start Date/Time
			$table->string('activityEndDate'); // Event End Date/Time
			$table->string('AllDayFlag'); // Event All Day Flag
			$table->string('imageUrlAdr'); // Event Image
			$table->string('lat'); // Event Latitude
			$table->string('lon'); // Event Longitude
			$table->timestamps();
		});

		Schema::create('activeCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->string('name');
			$table->string('long_name');
			$table->integer('source_category_id');
			$table->timestamps();
		});

		Schema::create('active_activeCategories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('active_id');
			$table->integer('activeCategories_id');
			$table->timestamps();
		});
	}
}
