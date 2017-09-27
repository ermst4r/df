<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsGoogleCountries;
use App\Entity\Repository\Contract\iAdwordsGoogleCountries;
use DB;


class AdwordsGoogleCountriesRepository implements iAdwordsGoogleCountries
{

    private $adwords_google_countries;

    /**
     * AdwordsGoogleCountriesRepository constructor.
     * @param AdwordsGoogleCountries $adwords_google_countries
     */
    public function __construct(AdwordsGoogleCountries $adwords_google_countries)
    {
        $this->adwords_google_countries = $adwords_google_countries;
    }


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createCountries($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->adwords_google_countries->create($data);
        } else {
            return $this->adwords_google_countries->where('id',$id)->update($data);
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCountries()
    {
       return $this->adwords_google_countries->all();
    }


    public function removeAllAdwordsCountries()
    {
        return $this->adwords_google_countries->truncate();
    }

}