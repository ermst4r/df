<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsKeyword {


    public function createKeyword($data,$id=0);
    public function getKeyword($fk_adwords_feed_id,$type,$visible=true);
    public function removeKeyword($id);
    public function getKeywordsFromFeed($fk_adwords_feed_id,$visible=true);
    public function getKeywordsWithNoConnectionToDelete($fk_adwords_feed_id);



}