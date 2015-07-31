<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHappAgeLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('happ_age_level', function(Blueprint $table)
		{
			$table->foreign('age_level_id')->references('id')->on('age_level')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('happ_id')->references('id')->on('happs')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('happ_age_level', function(Blueprint $table)
		{
			$table->dropForeign('happ_age_level_age_level_id_foreign');
			$table->dropForeign('happ_age_level_happ_id_foreign');
		});
	}

}
