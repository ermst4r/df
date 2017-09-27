<?php
namespace App\Entity\Repository\Contract;

interface iChannelFeed {


    public function createChannelFeed($data,$id=0);
    public function getChannelFeed($id=0);
    public function getActiveChannelsFromFeed($feed_id);
    public function removeChannelFeed($id);
    public function getActiveChannels($active= true);
    public function getCompleteChannelDetails($store_id);



}