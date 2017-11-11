<?php
namespace App\Entity\Repository\Contract;

interface iChannel {


    public function createChannel($data);
    public function getChannelByCountry($fk_country_id);
    public function getChannel($channel_id);


}