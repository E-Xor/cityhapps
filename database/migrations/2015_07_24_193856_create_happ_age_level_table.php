<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHappAgeLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('happ_age_level', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('happ_id')->unsigned()->index('happ_age_level_happ_id_foreign');
			$table->integer('age_level_id')->unsigned()->index('happ_age_level_age_level_id_foreign');
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
		Schema::drop('happ_age_level');
	}

}
