<?php

use Illuminate\Database\Seeder;



class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('nl_NL');


        DB::table('stores')->insert([
            'store_name' => ucfirst($faker->company()),
            'store_url' => $faker->url(),
            'created_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
            'updated_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
        ]);


    }
}
