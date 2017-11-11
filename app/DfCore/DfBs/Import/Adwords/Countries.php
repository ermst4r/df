<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

namespace App\DfCore\DfBs\Import\Adwords;

use App\DfCore\DfBs\Import\Remote\RemoteFileService;

use App\Entity\AdwordsGoogleCountries;
use App\Entity\AdwordsGoogleLanguages;
use App\Entity\Repository\AdwordsGoogleCountriesRepository;
use App\Entity\Repository\AdwordsGoogleLanguagesRepository;
use Carbon\Carbon;
use League\Csv\Reader;
/**
 * Let us instantiate a specific file
 * Class CsvMapping
 * @package App\DfCore\DfBs\Import\Csv
 */
class Countries  {



    /**
     * @param $channel
     * @return array
     * @throws \Exception
     */
    public static function importCountries()
    {

        $adwords_google_countries = new AdwordsGoogleCountriesRepository(new AdwordsGoogleCountries());
        $adwords_google_countries->removeAllAdwordsCountries();
        $reader = self::getCountries();
        $results = [];
        foreach($reader->fetch() as $row) {
            $results = [
                'criteria_id'=>$row[0],
                'country_name'=>$row[1],
                'country_code'=>$row[4],
                'created_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
                'updated_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
                ];
            $adwords_google_countries->createCountries($results);
        }
        return $results;
    }


    /**
     * @return static
     */
    public static function getCountries()
    {
        $file = RemoteFileService::getAdwordsStorageFolder().'/google_countries.csv';
        $reader = Reader::createFromPath($file);
        $reader->setDelimiter(";");
        return $reader;
    }


    /**
     * @return static
     */
    public static function getLanguages()
    {
        $file = RemoteFileService::getAdwordsStorageFolder().'/languagecodes.csv';
        $reader = Reader::createFromPath($file);
        $reader->setDelimiter(";");
        return $reader;
    }


    /**
     * @param $channel
     * @return array
     * @throws \Exception
     */
    public static function importLanguages()
    {

        $adwords_google_languages = new AdwordsGoogleLanguagesRepository(new AdwordsGoogleLanguages());
        $adwords_google_languages->removeAllLanguages();
        $reader =  self::getLanguages();
        $results = [];
        foreach($reader->fetch() as $row) {

            $results = [
                'language_name'=>$row[0],
                'language_code'=>$row[1],
                'criteria_id'=>$row[2],
                'created_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
                'updated_at'=>Carbon::now()->tz(DFBULDER_TIMEZONE),
            ];
            $adwords_google_languages->createLanguages($results);


        }
        return $results;
    }





}