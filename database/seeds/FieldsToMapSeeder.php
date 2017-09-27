<?php

use Illuminate\Database\Seeder;

class FieldsToMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        foreach(config('dfbuilder.fields_to_map') as $fields_to_map) {
            DB::table('fields_to_map')->insert([
                'field' => $fields_to_map,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }

    }
}
