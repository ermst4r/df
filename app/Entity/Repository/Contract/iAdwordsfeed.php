<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsfeed {


    public function getAdwordsFeed($id);
    public function createAdwordsFeed($data,$id=0);
    public function getAdwordsFeedFromFeedId($feed_id);
    public function removeAdwordsFeed($id);
    public function getAllAdwordsFeeds($active = true);
    public function getCompleteAdwordsFeeds($store_id);

}