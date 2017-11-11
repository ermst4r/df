<?php
namespace App\Entity\Repository\Contract;

interface iBolAds {


    public function createAds($data, $id = 0,$column='id');
    public function getAds($id,$by_feed=false);



}