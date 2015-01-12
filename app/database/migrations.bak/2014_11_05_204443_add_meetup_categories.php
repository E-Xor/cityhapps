<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeetupCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetupCategories', function(Blueprint $table)
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

		MeetupCategory::create(array('name' => 'Arts'));
		MeetupCategory::create(array('name' => 'Auto'));
		MeetupCategory::create(array('name' => 'Beliefs'));
		MeetupCategory::create(array('name' => 'Business'));
		MeetupCategory::create(array('name' => 'Community'));
		MeetupCategory::create(array('name' => 'Crafts'));
		MeetupCategory::create(array('name' => 'Dancing'));
		MeetupCategory::create(array('name' => 'Education'));
		MeetupCategory::create(array('name' => 'Fashion'));
		MeetupCategory::create(array('name' => 'Films'));
		MeetupCategory::create(array('name' => 'Fitness'));
		MeetupCategory::create(array('name' => 'Food & Drink'));
		MeetupCategory::create(array('name' => 'Games'));
		MeetupCategory::create(array('name' => 'Languages'));
		MeetupCategory::create(array('name' => 'LGBT'));
		MeetupCategory::create(array('name' => 'Lifestyle'));
		MeetupCategory::create(array('name' => 'Literature'));
		MeetupCategory::create(array('name' => 'Moms & Dads'));
		MeetupCategory::create(array('name' => 'Movements'));
		MeetupCategory::create(array('name' => 'Music'));
		MeetupCategory::create(array('name' => 'Outdoors'));
		MeetupCategory::create(array('name' => 'Paranormal'));
		MeetupCategory::create(array('name' => 'Pets'));
		MeetupCategory::create(array('name' => 'Photography'));
		MeetupCategory::create(array('name' => 'Sci fi'));
		MeetupCategory::create(array('name' => 'Singles'));
		MeetupCategory::create(array('name' => 'Social'));
		MeetupCategory::create(array('name' => 'Spirituality'));
		MeetupCategory::create(array('name' => 'Sports'));
		MeetupCategory::create(array('name' => 'Support'));
		MeetupCategory::create(array('name' => 'Tech'));
		MeetupCategory::create(array('name' => 'Well-being'));
		MeetupCategory::create(array('name' => 'Women'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('meetupCategories');
	}

}
