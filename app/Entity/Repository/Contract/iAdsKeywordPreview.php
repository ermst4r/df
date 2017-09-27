<?php
namespace App\Entity\Repository\Contract;

use App\DfCore\DfBs\Enum\AdwordsOptions;

interface iAdsKeywordPreview {

    public function createPreview($data,$id=0);
    public function keywordExistsInPreview($fk_adgroup_preview_id, $formatted_keyword,$keyword_type = AdwordsOptions::NORMAL_KEYWORD);
    public function setKeywordAsDeleteFromUpdate($fk_adgroup_preview_id);
    public function getDeletedKeywords($fk_adwords_feed_id,$adwords_id=0);
    public function removePreviewKeyword($id);
    public function getKeywordWithDetails($fk_adgroup_preview_id);
    public function getKeywordsToDeleteFromAdwords($fk_adwords_feed_id);
    public function setKeywordDeletedFromKeyword($keyword_id);

}