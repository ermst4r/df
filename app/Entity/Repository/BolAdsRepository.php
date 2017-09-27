<?php

namespace App\Entity\Repository;


use App\Entity\Bolads;
use App\Entity\CategoryFilter;
use App\Entity\Repository\Contract\iBolAds;
use DB;
class BolAdsRepository implements iBolAds
{

    private $bol_ads;

    /**
     * CategoryFilterRepository constructor.
     * @param CategoryFilter $categoryFilter
     */
    public function __construct(Bolads $bolads)
    {
        $this->bol_ads = $bolads;
    }





    /**
     * @param $data
     * @param int $id
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createAds($data, $id = 0,$column='id')
    {
        if($id == 0 ) {
            return $this->bol_ads->create($data);
        } else {
            return  $this->bol_ads->where($column,$id)->update($data);
        }
    }

    /**
     * @param $id
     * @param bool $by_feed

     */
    public function getAds($id, $by_feed = false)
    {
       if(!$by_feed){
           return $this->bol_ads->where('fk_bol_id',$id)->first();
       } else {
           return $this->bol_ads->where('fk_feed_id',$id)->get();
       }
    }






}