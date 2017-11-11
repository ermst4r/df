<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsGoogleCountries;
use App\Entity\Repository\Contract\iAdwordsGoogleCountries;
use DB;


class AdwordsGoogleCountriesRepository  extends Repository implements iAdwordsGoogleCountries
{

   


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createCountries($data, $id = 0)
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return $this->model->where('id',$id)->update($data);
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCountries()
    {
       return $this->model->all();
    }


    /**
     * @return void
     */
    public function removeAllAdwordsCountries()
    {
        return $this->model->truncate();
    }

}