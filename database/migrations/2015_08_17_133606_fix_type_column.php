<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('happs', function ($table) {
            $table->dropColumn('type');
            $table->enum('location_type', ['Indoor', 'Outdoor'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('happs', function ($table) {
            $table->dropColumn('location_type');
            $table->enum('type', ['Indoor', 'Outdoor', 'Mixed']);
        });
    }
}
