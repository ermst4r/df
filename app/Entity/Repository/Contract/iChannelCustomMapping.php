<?php
namespace App\Entity\Repository\Contract;

interface iChannelCustomMapping {


    public function createCustomChannel($data);
    public function removeCustomChannelMapping($fk_channel_feed_id,$fk_channel_type_id);
    public function getCustomFields($fk_channel_feed_id,$fk_channel_type_id);

}