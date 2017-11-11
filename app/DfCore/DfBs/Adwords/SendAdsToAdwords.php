<?php

namespace App\DfCore\DfBs\Adwords;
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */

use App\DfCore\DfBs\Enum\AdwordsOptions;
use App\Entity\AdCampaignPreview;
use App\Entity\AdsKeywordPreview;
use App\Entity\AdsPreview;
use App\Entity\AdwordsConfiguration;
use App\Entity\Adwordsfeed;
use App\Entity\AdwordsKeyword;
use App\Entity\AdwordsTarget;
use App\Entity\Repository\AdCampaignPreviewRepository;
use App\Entity\Repository\AdgroupPreviewRepository;
use App\Entity\Repository\AdsKeywordPreviewRepository;
use App\Entity\Repository\AdsPreviewRepository;
use App\Entity\Repository\AdwordsConfigurationRepository;
use App\Entity\Repository\AdwordsfeedRepository;
use App\Entity\Repository\AdwordsKeywordRepository;
use App\Entity\Repository\AdwordsTargetRepository;
use LaravelGoogleAds\Services\AdWordsService;

class SendAdsToAdwords
{


    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $adWordsService;

    /**
     * @var AdCampaignPreviewRepository
     */
    private $adwords_campaign_preview;

    /**
     * @var AdwordsApiWrapper
     */
    private $adwords_api_wrapper;

    /**
     * @var AdgroupPreviewRepository
     */
    private $adwords_adgroup_preview;

    /**
     * @var AdsPreviewRepository
     */
    private $ads_preview;

    /**
     * @var AdwordsKeywordRepository
     */
    private $keywords;
    /**
     * @var AdsKeywordPreviewRepository
     */
    private $adwords_keyword_preview;


    /**
     * @var AdwordsConfigurationRepository
     */
    private $adwords_configuration;

    /**
     * @var AdwordsTargetRepository
     */
    private $adwords_targeting;


    private $get_adwords_feed;

    /**
     * Adwords constructor.
     */
    public function __construct($fk_adwords_feed_id)
    {
        $this->adWordsService = new AdWordsService();
        $this->adwords_feed = new AdwordsfeedRepository(new Adwordsfeed());
        $this->get_adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
        $this->adwords_campaign_preview = new AdCampaignPreviewRepository(new AdCampaignPreview());
        $this->adwords_api_wrapper = new AdwordsApiWrapper($this->get_adwords_feed->adwords_account_id);
        $this->adwords_adgroup_preview = new AdgroupPreviewRepository(new \App\Entity\AdgroupPreview());
        $this->ads_preview = new AdsPreviewRepository(new AdsPreview());
        $this->keywords = new AdwordsKeywordRepository(new AdwordsKeyword());
        $this->adwords_keyword_preview = new AdsKeywordPreviewRepository(new AdsKeywordPreview());
        $this->adwords_configuration = new AdwordsConfigurationRepository(new AdwordsConfiguration());
        $this->adwords_targeting = new AdwordsTargetRepository(new AdwordsTarget());

    }


    /**
     * Mutate the ads.
     * @param $ads
     * @param $adgroup_adwords_id
     */
    public function mutateAds($ads,$adgroup_adwords_id,$live_option)
    {

        $ad_data = [
            'headline_1'=>$ads->headline_1,
            'headline_2'=>$ads->headline_2,
            'description'=>$ads->description,
            'path_1'=>$ads->path_1,
            'path_2'=>$ads->path_2,
            'final_urls'=>[$ads->final_url],
        ];
        $created_ad =  $this->adwords_api_wrapper->createAd($adgroup_adwords_id,$ad_data,$live_option);
        if($created_ad['status'] == true) {
            $this->ads_preview->createAdPreview(['adwords_api_message'=>null,'adwords_id'=>$created_ad['id']],$ads->id);

        } else {
            $this->ads_preview->createAdPreview(['adwords_api_message'=>$created_ad['message']],$ads->id);
        }

        return $created_ad['status'];
    }


    /**
     * @param $keywords_for_adwords
     * @param $adgroup_id
     */
    public function addKeywords($keywords_for_adwords,$adgroup_id)
    {

        foreach($keywords_for_adwords as $keywords)  {
            $the_keyword = $keywords->keyword;
            // exception for broad matching.
            if($keywords->keyword_type == AdwordsOptions::MOD_BROAD) {
                $the_keyword = '+'.implode(' +',explode(' ',$the_keyword));
            }
            $adwords_keyword_id = $this->adwords_api_wrapper->addKeyword($adgroup_id,$keywords,$keywords->keyword_type,$the_keyword);
            if(is_integer($adwords_keyword_id)) {
                $this->adwords_keyword_preview->createPreview(['adwords_id'=>$adwords_keyword_id],$keywords->keyword_preview_id);
            }

        }

    }

    /**
     * @param $adgroup
     * @return string
     */
    private function createAdgroup($adgroup,$campaign_id,$max_cpc)
    {

        $msg = 'failed';
        /**
         * Create a new adgroup in adwords
         */

        if($adgroup->adwords_id == 0 && $adgroup->delete_from_adwords == 0 ) {
            $created_adgroup =  $this->adwords_api_wrapper->createAdGroup($campaign_id,$adgroup->adgroup_name,$max_cpc);
            if($created_adgroup!=false) {
                $this->adwords_adgroup_preview->createAdgroupPreview(['adwords_id'=>$created_adgroup],$adgroup->id);
                $msg = $created_adgroup;
                /**
                 * Get the keywords and add them into the adgroup!
                 */
                $keywords_for_adwords = $this->adwords_keyword_preview->getKeywordWithDetails($adgroup->id);

                $this->addKeywords($keywords_for_adwords,$msg);
            }


            /**
             * Lets update the adgroup in adwords
             */
        } elseif($adgroup->adwords_id > 0 && $adgroup->delete_from_adwords == 0 ) {
            $this->adwords_api_wrapper->updateAdgroup($adgroup->adwords_id,$adgroup->adgroup_name);
            $msg = $adgroup->adwords_id;
            $keywords_for_adwords = $this->adwords_keyword_preview->getKeywordWithDetails($adgroup->id);
            $this->addKeywords($keywords_for_adwords,$msg);

        } elseif($adgroup->adwords_id >0 && $adgroup->delete_from_adwords > 0 ) {
            $msg  = 'error';
        }

        return $msg;


    }

    /**
     * @param $preview_campaign
     * @return string
     */
    private function createAdwordsCampaign($preview_campaign,$campaign_budget,$adwords_targeting,$live_option)
    {
        $msg = 'failed';


        /**
         * If we have an existing campaign id return that
         */
        if($preview_campaign->existing_campaign == 2) {
            return $preview_campaign->adwords_id;
        }

        /**
         * Create a new campaign in adwords
         */
        if($preview_campaign->adwords_id == 0 && $preview_campaign->delete_from_adwords == 0 ) {
            $campaign_adwords_id = $this->adwords_api_wrapper->createCampaign($preview_campaign->campaign_name,$campaign_budget,$adwords_targeting->campaign_type, $adwords_targeting->ad_delivery,$live_option);

            /**
             * When the campaign is new add the targeting...
             */
            if($campaign_adwords_id!=false) {
                $this->adwords_campaign_preview->createCampaignPreview(['adwords_id'=>$campaign_adwords_id],$preview_campaign->id);
                $this->adwords_api_wrapper->addTargetingToCampaign($campaign_adwords_id,$adwords_targeting->target_countries,$adwords_targeting->target_languages);
                $msg = $campaign_adwords_id;
            }

            /**
             * Lets update the campaign in adwords
             */
        } elseif($preview_campaign->adwords_id > 0 && $preview_campaign->delete_from_adwords == 0 ) {
            $this->adwords_api_wrapper->updateCampaign($preview_campaign->adwords_id,$preview_campaign->campaign_name);
            $msg = $preview_campaign->adwords_id;

            /**
             * Remove
             */
        } elseif($preview_campaign->adwords_id >0 && $preview_campaign->delete_from_adwords > 0 && $preview_campaign->existing_campaign == 1 ) {

            $msg  = 'error';
        }

        return $msg;
    }


    /**
     * Remove adwords
     * @param $fk_adwords_feed_id
     */
    public function deleteAdwordsItems($fk_adwords_feed_id)
    {

        /**
         * First delete the keywords per adgroup...
         */
        foreach($this->adwords_keyword_preview->getKeywordsToDeleteFromAdwords($fk_adwords_feed_id) as $delete_from_adwords){
            $is_deleted = $this->adwords_api_wrapper->removeKeyword($delete_from_adwords->keyword_adwords_id,$delete_from_adwords->adgroup_adwords_id);
            if($is_deleted != false) {
                $this->adwords_keyword_preview->removePreviewKeyword($delete_from_adwords->keyword_id);
                $this->keywords->removeKeyword($delete_from_adwords->fk_adwords_keyword_id);

            }
        }
        /**
         * Check which campaigns to delete
         */
        foreach($this->adwords_campaign_preview->getCampaignsToDelete($fk_adwords_feed_id) as $campaigns_to_delete) {
            $remove_campaign = $this->adwords_api_wrapper->removeCampaign($campaigns_to_delete->adwords_id);
            if($remove_campaign !=false ) {
                $this->adwords_campaign_preview->removeSingleCampaign($campaigns_to_delete->id);
            }
        }


        /**
         * CHeck which adgroups to delete
         */
        foreach($this->adwords_adgroup_preview->getAdgroupsToDelete($fk_adwords_feed_id) as $adgroups_to_delete) {
            $remove_adgroup = $this->adwords_api_wrapper->removeAdgroup($adgroups_to_delete->adwords_id);
            if($remove_adgroup != false) {
                $this->adwords_adgroup_preview->removeSingleAdgroup($adgroups_to_delete->id);
            }
        }




        /**
         * Check which ads to deleted
         */
        foreach ($this->ads_preview->getAdsToDelete($fk_adwords_feed_id) as $ads_to_delete) {
            $remove_ad = $this->adwords_api_wrapper->removeAd($ads_to_delete->ads_adwords_id,$ads_to_delete->adgroup_adwords_id);
            if($remove_ad != false) {
                $this->ads_preview->removeSingleAd($ads_to_delete->id);

            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute()
    {


        /**
         * Get the campaigns
         */
        $valid_adwords = $this->adwords_api_wrapper->getLanguages();
        if($valid_adwords != false ) {
            $this->adwords_feed->createAdwordsFeed(['adwords_api_message'=>null],$this->get_adwords_feed->id);
            $fk_adwords_feed_id = $this->get_adwords_feed->id;
            $adwords_targeting = $this->adwords_targeting->getAdwordsTarget($fk_adwords_feed_id);
            $preview_campaigns = $this->adwords_campaign_preview->getPreviewCampaigns($fk_adwords_feed_id,false);
            $adwords_configuration  = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
            $live_option   = $adwords_configuration->live_option;
            $this->deleteAdwordsItems($fk_adwords_feed_id);


            foreach($preview_campaigns as $preview_campaign) {
                // create the campaign
                $create_campaign = $this->createAdwordsCampaign($preview_campaign,$adwords_configuration->daily_budget,$adwords_targeting,$live_option);
                $campaign_adwords_id = (int) $create_campaign;
                if($create_campaign != 'error') {
                    // loop through the adgroups
                    // and created them
                    foreach($this->adwords_adgroup_preview->getAdgroupDataFromCampaigns($preview_campaign->id,false) as $adgroup) {
                        $created_adgroup = $this->createAdgroup($adgroup,$campaign_adwords_id,$adwords_configuration->cpc);

                        $adgroup_adwords_id = (int) $created_adgroup;
                        /**
                         * Create an ad
                         */
                        if($created_adgroup !='error') {
                            foreach($this->ads_preview->getValidActiveAdwordsAds($preview_campaign->id,$adgroup->id) as $ads) {
                                $this->mutateAds($ads,$adgroup_adwords_id,$live_option);

                            }
                        }

                    }
                }
            }
        } else {
            $this->adwords_feed->createAdwordsFeed(['adwords_api_message'=>trans('messages.error_lbl_1')],$this->get_adwords_feed->id);
        }
    }
}
