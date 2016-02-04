<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 255)->index();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Import the pre-existing front-page data from this point in time
        \DB::table('site_settings')->insert([
            [
                'key' => 'welcome_message',
                'value' => <<<PAGEDATA
<h1>CityHapps is here for families to find fun, safe, and affordable &#8220;Happs&#8221; to experience!</h1>  <p>It&#8217;s our goal to bring the most accurate information and member generated knowledge on all things kids directly to your fingertips. Skip the search engine and create a CityHapps user account today.  You will receive more information on upcoming events for kids and families in your area.</p> <p><strong>We promise, you&#8217;ll be glad you did!</strong></p>
PAGEDATA
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_settings');
    }
}
