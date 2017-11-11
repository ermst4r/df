<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsAd {


    public function createAds($data,$id=0);
    public function getAdwordsAds($fk_adwords_feed_id);
    public function removeAd($id);
    public function getBackupTemplate($ad_id);
    public function getAd($id);
    public function removeParentAd($parent_id);

}