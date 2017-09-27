<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsGoogleCountries;
use App\Entity\AdwordsGoogleLanguages;
use App\Entity\Repository\Contract\iAdwordsGoogleLanguages;
use DB;


class AdwordsGoogleLanguagesRepository implements iAdwordsGoogleLanguages
{

    private $adwords_google_languages;

    /**
     * AdwordsGoogleCountriesRepository constructor.
     * @param AdwordsGoogleCountries $adwords_google_countries
     */
    public function __construct(AdwordsGoogleLanguages $adwords_google_languages)
    {
        $this->adwords_google_languages = $adwords_google_languages;
    }


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createLanguages($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->adwords_google_languages->create($data);
        } else {
            return $this->adwords_google_languages->where('id',$id)->update($data);
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLanguages()
    {
        return $this->adwords_google_languages->all();
    }


    public function removeAllLanguages()
    {
        return $this->adwords_google_languages->truncate();
    }

}