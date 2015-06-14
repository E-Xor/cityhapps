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
              $table->integer('minimum_age')->default(0); // example 0
              $table->integer('maximum_age')->default(0); // example 2
          }
        );

        //We're now going to insert some data, into it, we need at least 1 record otherwise the FK will fail
        //Delete previous data, this only runs once per instance
        DB::table('event_age_level')->delete();

        //Defaults
        $seedData = [
            //This to permit the FK to exist on previously migrated data
          ['No Limit', 0, 150],
          ['Infants', 1, 2],
          ['Toddlers', 3, 9],
          ['Children', 10, 11],
          ['PreTeen', 12, 19],
          ['Young Adult', 20, 21],
          ['Adult', 21, 64],
            //Super old, yet still hitting the dance floor
          ['Senior', 65, 150],


        ];
        foreach ($seedData as $data) {
            DB::table('event_age_level')->insert(
              [
                "name" => $data[0],
                "minimum_age" => $data[1],
                "maximum_age" => $data[2],
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
