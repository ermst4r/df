<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
#use Faker\Provider\nl_NL as Faker;


class ToCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Insert Google Shopping categories on insert
         */
       $google_shopping =  \App\DfCore\DfBs\Import\Category\CategoryImportFactory::setChannel(\App\DfCore\DfBs\Enum\CategoryChannels::GOOGLE_SHOPPING);
       foreach($google_shopping as $g_data) {
           DB::table('category')->insert($g_data);
       }




    }
}
