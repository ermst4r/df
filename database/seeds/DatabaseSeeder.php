<?php

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StoreSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(FieldsToMapSeeder::class);
        $this->call(ToCategorySeeder::class);
        $this->call(AdwordsCountrySeeder::class);
    }
}
