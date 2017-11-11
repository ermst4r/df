<?php
namespace App\Entity\Repository\Contract;

interface iBolFeed {


    public function createBolFeed($data, $id=0);
    public function getBolFeed($id,$by_feed=false);
    public function getCompleteBolFeed($store_id);
    public function removeBolFeed($id);



}