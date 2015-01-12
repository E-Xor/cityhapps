<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoryNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Category::where('name', '=', 'Arts, Creative, Culture')->update(array('name' => 'Arts'));
		Category::where('name', '=', 'Business & Networking')->update(array('name' => 'Business'));
		Category::where('name', '=', 'Concerts & Music')->update(array('name' => 'Music'));
		Category::where('name', '=', 'Organizations & Meetups')->update(array('name' => 'Meetups'));
		Category::where('name', '=', 'Family & Education')->update(array('name' => 'Family'));
		Category::where('name', '=', 'Movies & Film')->update(array('name' => 'Movies'));
		Category::where('name', '=', 'Health & Wellness')->update(array('name' => 'Health'));
		Category::where('name', '=', 'Holiday & Seasonal')->update(array('name' => 'Seasonal'));
		Category::where('name', '=', 'Community & Culture')->update(array('name' => 'Community'));
		Category::where('name', '=', 'Sports & Outdoors')->update(array('name' => 'Sports'));
		Category::where('name', '=', 'Science & Technology')->update(array('name' => 'Science & Tech'));
		Category::where('name', '=', 'Religion & Spirituality')->update(array('name' => 'Religion'));
		Category::where('name', '=', 'Politics & Government')->update(array('name' => 'Politics'));
		Category::where('name', '=', 'Nightlife & Singles')->update(array('name' => 'Nightlife'));
		Category::where('name', '=', 'Home & Lifestyle')->update(array('name' => 'Lifestyle'));
		Category::where('name', '=', 'Hobbies & Crafts')->update(array('name' => 'Hobbies'));
		Category::where('name', '=', 'Literature & Books')->update(array('name' => 'Books'));
		Category::where('name', '=', 'Museums & Attractions')->update(array('name' => 'Attractions'));
		Category::where('name', '=', 'Pets & Animals')->update(array('name' => 'Pets'));
		Category::where('name', '=', 'Auto, Boat & Air')->update(array('name' => 'Auto'));

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
