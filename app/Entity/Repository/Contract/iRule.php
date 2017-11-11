<?php
namespace App\Entity\Repository\Contract;

interface iRule {


    public function createRule($data = array(),$id = 0);
    public function getRule($id=0,$multi=false);
    public function removeRule($id);
    public function getOrderdRules($id,$order='asc',$is_channel_feed = false);
    public function getRuleIdsFromChannel($fk_channel_feed_id);
    public function getChannelOrdersRules($fk_channel_feed_id,$fk_channel_type_id=0,$order='asc');
    public function getBolOrderdRules($bol_id,$order='asc');
    public function getAdwordsOrderRules($adwords_feed_id=0,$order='asc');
    public function getRuleIdFromAdwords($adwords_feed_id);
    public function getRuleIdsFromBol($bol_id);



}