<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventbriteCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventbriteCategories', function(Blueprint $table)
		{
			//$table->string('start_time');
			//$table->boolean('tz_id');
			//$table->longText('description');

			$table->increments('id');
			$table->integer('category_id');
			$table->string('name');
			$table->string('long_name');
			$table->integer('source_category_id');
			$table->timestamps();
		});

		EventbriteCategory::create(array('name' => 'Music'));
		EventbriteCategory::create(array('name' => 'Business'));
		EventbriteCategory::create(array('name' => 'Food & Drink'));
		EventbriteCategory::create(array('name' => 'Community'));
		EventbriteCategory::create(array('name' => 'Arts'));
		EventbriteCategory::create(array('name' => 'Film & Media'));
		EventbriteCategory::create(array('name' => 'Sports & Fitness'));
		EventbriteCategory::create(array('name' => 'Health'));
		EventbriteCategory::create(array('name' => 'Science & Tech'));
		EventbriteCategory::create(array('name' => 'Travel & Outdoor'));
		EventbriteCategory::create(array('name' => 'Charity & Causes'));
		EventbriteCategory::create(array('name' => 'Spirituality'));
		EventbriteCategory::create(array('name' => 'Family & Education'));
		EventbriteCategory::create(array('name' => 'Holiday'));
		EventbriteCategory::create(array('name' => 'Government & Politics'));
		EventbriteCategory::create(array('name' => 'Fashion & Beauty'));
		EventbriteCategory::create(array('name' => 'Home & Lifestyle'));
		EventbriteCategory::create(array('name' => 'Auto, Boat & Air'));
		EventbriteCategory::create(array('name' => 'Hobbies & Special Interest'));
		EventbriteCategory::create(array('name' => 'Other'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eventbriteCategories');
	}

}
