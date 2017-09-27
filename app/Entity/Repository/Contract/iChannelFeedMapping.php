<?php
namespace App\Entity\Repository\Contract;

interface iChannelFeedMapping {


    public function createChannelFeedMapping($data);
    public function removeChannelFieldMapping($fk_channel_feed_id);
    public function getMappedItems($fk_channel_feed_id,$fk_channel_type_id);
    public function getMappingTemplate($fk_channel_feed_id,$fk_channel_type_id,$to_array = true);
    public function hasDuplicateFieldName($fk_channel_feed_id,$fk_channel_type_id,$feed_row_name);
    public function spreadSheetDuplicateColumnHelper($fk_channel_feed_id,$fk_channel_type_id);



}