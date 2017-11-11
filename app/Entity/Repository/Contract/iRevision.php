<?php
namespace App\Entity\Repository\Contract;

interface iRevision {


    public function setUpdateRevision($data = array());
    public function setDeleteRevision($ids,$feed_id,$channel_feed_id,$channel_type_id);
    public function getDeletedRevisionData($fk_channel_feed_id);
    public function getUpdatedRevisionData($fk_channel_feed_id);




}