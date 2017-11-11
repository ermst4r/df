<?php
namespace App\Entity\Repository\Contract;

interface iAdsPreview {
    public function createAdPreview($data,$id=0);
    public function getAdsFromCampaignAndAdgroup($fk_campaigns_preview_id,$fk_adgroup_preview_id);
    public function getPreviewAdsArrayMerged($fk_campaigns_preview_id,$fk_adgroup_preview_id,$fk_adwords_ad_id);
    public function removeInvalidAds($fk_campaigns_preview_id);
    public function removeAdByProductId($id,$fk_adwords_ad_id);

    public function updateAdByMultipleId($data,$id,$fk_adwords_ad_id);
    public function getHotPreviewAds($fk_adwords_feed_id,$fk_campaigns_preview_id,$fk_adgroup_preview_id);
    public function removeAdPreview($id);
    public function getAd($id);
    public function getValidActiveAdwordsAds($fk_campaigns_preview_id, $fk_adgroup_preview_id, $is_valid=true);
    public function getAdsToDelete($fk_adwords_feed_id);
    public  function removeSingleAd($id);
    public function getPreviewAdWordsOptions($fk_campaigns_preview_id,$fk_adgroup_preview_id,$fk_adwords_ad_id);
    public function getAdsApiErrors($fk_campaigns_preview_id, $fk_adgroup_preview_id);
    public function countPreviewAds($fk_adwords_feed_id);


}