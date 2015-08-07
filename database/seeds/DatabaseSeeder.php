<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CategorySeeder::class);
        $this->call(EventbriteCategorySeeder::class);
        $this->call(EventfulCategorySeeder::class);
        $this->call(MeetupCategorySeeder::class);

        $this->call(AgeLevelSeeder::class);

        Model::reguard();
    }
}
