<?php

use Illuminate\Database\Seeder;

class AgeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('age_level')->insert(["name" => 'Infants']);
        DB::table('age_level')->insert(["name" => 'Toddlers']);
        DB::table('age_level')->insert(["name" => 'Child']);
        DB::table('age_level')->insert(["name" => 'PreTeen']);
        DB::table('age_level')->insert(["name" => 'Young Adult']);
        DB::table('age_level')->insert(["name" => 'Adult']);
        DB::table('age_level')->insert(["name" => 'Senior']);
    }
}
