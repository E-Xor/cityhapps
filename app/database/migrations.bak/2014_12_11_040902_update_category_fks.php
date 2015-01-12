<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoryFks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$Arts = Category::where('name', '=', 'Arts')->first();
		$Sports = Category::where('name', '=', 'Sports')->first();
		$Religion = Category::where('name', '=', 'Religion')->first();
		

		DB::table('activeCategories')->where('name', '=', 'swimming pools/aquatic centers')->update(array('category_id' => $Sports->id));
		DB::table('meetupCategories')->where('name', '=', 'Games')->update(array('category_id' => $Arts->id));
		DB::table('meetupCategories')->where('name', '=', 'Paranormal')->update(array('category_id' => $Arts->id));
		DB::table('meetupCategories')->where('name', '=', 'Sci fi')->update(array('category_id' => $Arts->id));
		DB::table('meetupCategories')->where('name', '=', 'Spirituality')->update(array('category_id' => $Religion->id));
		
		
		
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
