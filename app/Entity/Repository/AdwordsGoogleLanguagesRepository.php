<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsGoogleCountries;
use App\Entity\AdwordsGoogleLanguages;
use App\Entity\Repository\Contract\iAdwordsGoogleLanguages;
use DB;


class AdwordsGoogleLanguagesRepository extends Repository implements iAdwordsGoogleLanguages
{

   


    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createLanguages($data, $id = 0)
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
    public function getLanguages()
    {
        return $this->model->all();
    }


    public function removeAllLanguages()
    {
        return $this->model->truncate();
    }

}