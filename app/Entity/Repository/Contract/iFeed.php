<?php
namespace App\Entity\Repository\Contract;

interface iFeed {



    public function getFeed($id);
    public function createFeed($data = array(),$id = 0);
    public function getFeedRules($id);
    public function getFeedByStore($store_id, $limit=0);
    public function getAllActiveFeeds($active = true );


}