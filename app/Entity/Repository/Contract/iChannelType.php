<?php
namespace App\Entity\Repository\Contract;

interface iChannelType {


    public function createChannelType($data);
    public function getChannelTypeByChannel($channel_id);
    public function removeChannelType($channel_id);


}