<?php
namespace App\Entity\Repository\Contract;

interface iAdgroupPreview {
    public function createAdgroupPreview($data);
    public function getAdgroupData($fk_adwords_feed_id);
    public function getAdgroupFromCampaign($campaign_id);
    public function getAdgroupPreviewByName($adgroup_name,$campaign_id);
    public function getAdgroupDataFromCampaigns($fk_campaigns_preview_id,$to_array = true);
    public function removeSingleAdgroup($id);


}