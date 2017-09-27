<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsConfiguration {


    public function createAdwordsConfiguration($data,$id=0);
    public function hasAdwordsConfiguration($fk_adwords_feed_id);

    public function getAdwordsConfiguration($fk_adwords_feed_id);

}