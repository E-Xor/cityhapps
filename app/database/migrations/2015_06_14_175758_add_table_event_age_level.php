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
          'event_age_level',
          function (Blueprint $table) {
              $table->increments('id');
              $table->string('name')->unique(); //Name, example Infants
          }
        );

        //We're now going to insert some data, into it, we need at least 1 record otherwise the FK will fail
        //Delete previous data, this only runs once per instance
        DB::table('event_age_level')->delete();

        //Defaults
        //This to permit the FK to exist on previously migrated data
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
            DB::table('event_age_level')->insert(
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
        Schema::drop('event_age_level');
    }

}
