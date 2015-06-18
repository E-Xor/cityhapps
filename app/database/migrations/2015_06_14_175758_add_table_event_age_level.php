<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableEventAgeLevel extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
          'age_level',
          function (Blueprint $table) {
              $table->increments('id');
              $table->string('name')->unique(); //Name, example Infants
          }
        );

        //We're now going to insert some data, into it, we need at least 1 record otherwise the FK in events will fail
        //Delete previous data, this only runs once per instance
        DB::table('age_level')->delete();

        //Defaults
        $seedData = [
          'No Limit',
          'Infants',
          'Toddlers',
          'PreTeen',
          'Young Adult',
          'Adult',
          'Senior',
        ];

        foreach ($seedData as $data) {
            DB::table('age_level')->insert(
              [
                "name" => $data
              ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('age_level');
    }

}
