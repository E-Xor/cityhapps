<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeetupCategoriesWithIds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('meetupCategories')->where('name', '=', 'Arts')->update(array('source_category_id' => '1'));
		DB::table('meetupCategories')->where('name', '=', 'Auto')->update(array('source_category_id' => '3'));
		DB::table('meetupCategories')->where('name', '=', 'Beliefs')->update(array('source_category_id' => '28'));
		DB::table('meetupCategories')->where('name', '=', 'Business')->update(array('source_category_id' => '2'));
		DB::table('meetupCategories')->where('name', '=', 'Community')->update(array('source_category_id' => '4'));
		DB::table('meetupCategories')->where('name', '=', 'Crafts')->update(array('source_category_id' => '15'));
		DB::table('meetupCategories')->where('name', '=', 'Dancing')->update(array('source_category_id' => '5'));
		DB::table('meetupCategories')->where('name', '=', 'Education')->update(array('source_category_id' => '6'));
		DB::table('meetupCategories')->where('name', '=', 'Fashion')->update(array('source_category_id' => '8'));
		DB::table('meetupCategories')->where('name', '=', 'Films')->update(array('source_category_id' => '20'));
		DB::table('meetupCategories')->where('name', '=', 'Fitness')->update(array('source_category_id' => '9'));
		DB::table('meetupCategories')->where('name', '=', 'Food & Drink')->update(array('source_category_id' => '10'));
		DB::table('meetupCategories')->where('name', '=', 'Games')->update(array('source_category_id' => '11'));
		DB::table('meetupCategories')->where('name', '=', 'Languages')->update(array('source_category_id' => '16'));
		DB::table('meetupCategories')->where('name', '=', 'LGBT')->update(array('source_category_id' => '12'));
		DB::table('meetupCategories')->where('name', '=', 'Lifestyle')->update(array('source_category_id' => '17'));
		DB::table('meetupCategories')->where('name', '=', 'Literature')->update(array('source_category_id' => '18'));
		DB::table('meetupCategories')->where('name', '=', 'Moms & Dads')->update(array('source_category_id' => '25'));
		DB::table('meetupCategories')->where('name', '=', 'Movements')->update(array('source_category_id' => '13'));
		DB::table('meetupCategories')->where('name', '=', 'Music')->update(array('source_category_id' => '21'));
		DB::table('meetupCategories')->where('name', '=', 'Outdoors')->update(array('source_category_id' => '23'));
		DB::table('meetupCategories')->where('name', '=', 'Paranormal')->update(array('source_category_id' => '24'));
		DB::table('meetupCategories')->where('name', '=', 'Pets')->update(array('source_category_id' => '26'));
		DB::table('meetupCategories')->where('name', '=', 'Photography')->update(array('source_category_id' => '27'));
		DB::table('meetupCategories')->where('name', '=', 'Sci fi')->update(array('source_category_id' => '29'));
		DB::table('meetupCategories')->where('name', '=', 'Singles')->update(array('source_category_id' => '30'));
		DB::table('meetupCategories')->where('name', '=', 'Social')->update(array('source_category_id' => '31'));
		DB::table('meetupCategories')->where('name', '=', 'Spirituality')->update(array('source_category_id' => '22'));
		DB::table('meetupCategories')->where('name', '=', 'Sports')->update(array('source_category_id' => '32'));
		DB::table('meetupCategories')->where('name', '=', 'Support')->update(array('source_category_id' => '33'));
		DB::table('meetupCategories')->where('name', '=', 'Tech')->update(array('source_category_id' => '34'));
		DB::table('meetupCategories')->where('name', '=', 'Well-being')->update(array('source_category_id' => '14'));
		DB::table('meetupCategories')->where('name', '=', 'Women')->update(array('source_category_id' => '35'));
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
