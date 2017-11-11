<?php
namespace App\Entity\Repository\Contract;

interface iAdwordsRevision {


    public function createRevision($data,$id);
    public function addRevision($data);
    public function setDeleteRevision($ids,$fk_ads_preview_id,$fk_adwords_feed_id);
    public function getUpdatedRevisions($fk_adwords_feed_id);
    public function getDeleteRevisions($fk_adwords_feed_id);

}