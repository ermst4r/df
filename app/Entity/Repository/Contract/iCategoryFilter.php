<?php
namespace App\Entity\Repository\Contract;

interface iCategoryFilter {


    public function getCategoryFilter($id);
    public function createCategoryFilter($data = array(), $id=0);
    public function getCategoryFilterFromFeed($id,$channel_feed_level=false);
    public function deleteFilter($filter_id);
    public function getCatIdsFromChannel($fk_channel_feed_id,$visible = null);
    public function getChannelCategories($id,$from_feed_id = false);
    public function getBolCategories($id,$from_feed_id = false);
    public function getCatIdsFromBol($bol_id);

}