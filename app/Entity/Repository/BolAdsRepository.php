<?php

namespace App\Entity\Repository;


use App\Entity\Bolads;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iBolAds;
use DB;
class BolAdsRepository extends Repository implements iBolAds
{







    /**
     * @param $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createAds($data, $id = 0,$column='id')
    {
        if($id == 0 ) {
            return $this->model->create($data);
        } else {
            return  $this->model->where($column,$id)->update($data);
        }
    }

    /**
     * @param $id
     * @param bool $by_feed

     */
    public function getAds($id, $by_feed = false)
    {
       if(!$by_feed){
           return $this->model->where('fk_bol_id',$id)->first();
       } else {
           return $this->model->where('fk_feed_id',$id)->get();
       }
    }






}