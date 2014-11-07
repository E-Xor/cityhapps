<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('Categories')->delete();

		Category::create(array('name' => 'Arts, Creative, Culture'));
		Category::create(array('name' => 'Business & Networking'));
		Category::create(array('name' => 'Comedy'));
		Category::create(array('name' => 'Concerts & Music'));
		Category::create(array('name' => 'Organizations & Meetups'));
		Category::create(array('name' => 'Family & Education'));
		Category::create(array('name' => 'Movies & Film'));
		Category::create(array('name' => 'Food & Wine'));
		Category::create(array('name' => 'Charity'));
		Category::create(array('name' => 'Health & Wellness'));
		Category::create(array('name' => 'Holiday & Seasonal'));
		Category::create(array('name' => 'Community & Culture'));
		Category::create(array('name' => 'Sports & Outdoors'));
		Category::create(array('name' => 'Science & Technology'));
		Category::create(array('name' => 'Religion & Spirituality'));
		Category::create(array('name' => 'Politics & Government'));
		Category::create(array('name' => 'Nightlife & Singles'));
		Category::create(array('name' => 'Fashion & Beauty'));
		Category::create(array('name' => 'Home & Lifestyle'));
		Category::create(array('name' => 'Hobbies & Crafts'));
		Category::create(array('name' => 'Literature & Books'));
		Category::create(array('name' => 'Museums & Attractions'));
		Category::create(array('name' => 'Pets & Animals'));
		Category::create(array('name' => 'Auto, Boat & Air'));
		Category::create(array('name' => 'Other'));

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
