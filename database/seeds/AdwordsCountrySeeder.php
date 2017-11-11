<?php

use Illuminate\Database\Seeder;

class AdwordsCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $reader = \App\DfCore\DfBs\Import\Adwords\Countries::getCountries();
        foreach($reader->fetch() as $row) {
            $results = [
                'criteria_id'=>$row[0],
                'country_name'=>$row[1],
                'country_code'=>$row[4],
                'created_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
                'updated_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
            ];
            DB::table('adwords_google_countries')->insert($results);

        }


        $languages = \App\DfCore\DfBs\Import\Adwords\Countries::getLanguages();
        foreach($languages->fetch() as $row) {
            $results = [
                'language_name'=>$row[0],
                'language_code'=>$row[1],
                'criteria_id'=>$row[2],
                'created_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
                'updated_at'=>\Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE),
            ];
            DB::table('adwords_google_languages')->insert($results);

        }

    }
}
