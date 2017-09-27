<?php
namespace App\Entity\Repository\Contract;

interface iAdCampaignPreview {
    public function createCampaignPreview($data,$id=0);
    public function removeAdCampaignPreview($id);
    public function getAdgroups($fk_adwords_feed_id);
    public function campaignExists($fk_adwords_feed_id,$campaign_name);
    public function getPreviewCampaigns($fk_adwords_feed_id, $to_array = true);
    public function getCampaignsToDelete($fk_adwords_feed_id);
    public function removeSingleCampaign($id);
    public function removeExistingCampaignFromPreview($fk_adwords_feed_id,$existing_campaign);


}