<?php
namespace App\Entity\Repository\Contract;

interface iChannelMapping {


    public function createChannelMapping($data);
    public function getChannelMappings($fk_channel_id,$fk_channel_type_id,$pluck_value=false);



}