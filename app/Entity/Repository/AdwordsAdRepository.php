<?php

namespace App\Entity\Repository;


use App\Entity\AdwordsAd;
use App\Entity\Repository\Contract\iAdwordsAd;


class AdwordsAdRepository implements iAdwordsAd
{

    private $adwords_ad;

    /**
     * AdwordsAdRepository constructor.
     * @param AdwordsAd $adwords_ad
     */
    public function __construct(AdwordsAd $adwords_ad)
    {
        $this->adwords_ad = $adwords_ad;
    }




    public function removeParentAd($parent_id)
    {
        return $this->adwords_ad->where('parent_id',$parent_id)->delete();
    }

    /**
     * @param $ad_id
     * @return mixed
     */
    public function countAds($ad_id)
    {
        return $this->adwords_ad->where('parent_id',$ad_id)->count();
    }

    /**
     * @param $data
     * @param int $id
     * @return mixed
     */
    public function createAds($data,$id=0)
    {
        if($id == 0 ) {
           return  $this->adwords_ad->create($data);
        } else {
            return $this->adwords_ad->where('id',$id)->update($data);
        }

    }

    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdwordsAds($fk_adwords_feed_id)
    {
        return $this->adwords_ad->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('is_backup_template',false)->where('parent_id',0)->orderBy('created_at','asc')->get();
    }


    /**
     * @param $ad_id
     * @return mixed
     */
    public function getBackupTemplate($ad_id)
    {
        return $this->adwords_ad->where('is_backup_template',true)->where('parent_id',$ad_id)->get();
    }


    /**
     * @param $id
     * @return mixed
     */

    public function getAd($id)
    {
        return $this->adwords_ad->find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removeAd($id)
    {
        if($id > 0 ) {
            return $this->adwords_ad->where('id',$id)->delete();
        } else {
            return false;
        }

    }


}