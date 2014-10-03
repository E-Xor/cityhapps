<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToUserCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_categories', function(Blueprint $table)
		{
			$table->integer("user_id")->unsigned();
			$table->integer("category_id")->unsigned();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_categories', function(Blueprint $table)
		{
			$table->dropColumn("user_id");
			$table->dropColumn("category_id");
		});
	}

}
