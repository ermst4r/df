<?php
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

namespace App\Console\Commands\TestingJobs;

use App\DfCore\DfBs\Adwords\AdgroupPreview;
use App\DfCore\DfBs\Adwords\AdwordsHelpers;
use App\DfCore\DfBs\Adwords\AdwordsValidator;
use App\DfCore\DfBs\Adwords\SendAdsToAdwords;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\ESIndexTypes;
use App\DfCore\DfBs\Rules\Builder\FeedOperationDirector;
use App\ElasticSearch\DynamicFeedRepository;
use App\ElasticSearch\ESAdwords;
use App\Entity\AdCampaignPreview;
use App\Entity\AdsKeywordPreview;
use App\Entity\AdsPreview;
use App\Entity\AdwordsAd;
use App\Entity\AdwordsConfiguration;
use App\Entity\Adwordsfeed;
use App\Entity\AdwordsKeyword;
use App\Entity\AdwordsRevision;
use App\Entity\Repository\AdCampaignPreviewRepository;
use App\Entity\Repository\AdgroupPreviewRepository;
use App\Entity\Repository\AdsKeywordPreviewRepository;
use App\Entity\Repository\AdsPreviewRepository;
use App\Entity\Repository\AdwordsAdRepository;
use App\Entity\Repository\AdwordsConfigurationRepository;
use App\Entity\Repository\AdwordsfeedRepository;
use App\Entity\Repository\AdwordsKeywordRepository;
use App\Entity\Repository\AdwordsRevisionRepository;
use Illuminate\Console\Command;



/**
 * Class AdwordsPreview
 * @package App\Console\Commands
 */
class PushToAdwords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push_to_adwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a preview and push the ads to adwords! Job is also availble..';

    /**
     * @var AdwordsfeedRepository
     */
    private $adwords_feed;

    /**
     * @var
     */
    private $adwords_configuration;

    /**
     * @var
     */
    private $adwords_ad;

    /**
     * @var
     */
    private $ad_campaign_preview;
    /**
     * @var
     */
    private $adgroup_preview;
    /**
     * @var
     */
    private $ads_preview;
    /**
     * @var AdwordsKeywordRepository
     */
    private $adwords_keyword;
    /**
     * @var AdsKeywordPreviewRepository
     */
    private $adwords_keyword_preview;


    /**
     * AdwordsPreview constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->adwords_feed = new AdwordsfeedRepository( new Adwordsfeed());
        $this->adwords_configuration = new AdwordsConfigurationRepository(new AdwordsConfiguration());
        $this->adwords_ad = new AdwordsAdRepository(new AdwordsAd());
        $this->ad_campaign_preview = new AdCampaignPreviewRepository(new AdCampaignPreview());
        $this->adgroup_preview = new AdgroupPreviewRepository(new \App\Entity\AdgroupPreview());
        $this->ads_preview = new AdsPreviewRepository(new AdsPreview());
        $this->adwords_keyword = new AdwordsKeywordRepository(new AdwordsKeyword());
        $this->adwords_keyword_preview = new AdsKeywordPreviewRepository(new AdsKeywordPreview());

    }


    /**
     * @param $a
     * @return array
     */
    private function printAllVals($a) {

        $returnArray = [];
        if(isset($a['name'])) {
            foreach($a['name']['buckets'] as $v) {
                $returnArray[$v['key']]  = $this->printAllVals($v);
            }
        }
        return $returnArray;

    }


    /**
     * Prepare the aggegrated result from ES
     * @param $tags
     * @param $ag_vals
     * @return array
     */
    private function prepareGroupNameArray($tags,$ag_vals)
    {
        if(count($tags) <=1)  {
            $get_group_names = array_keys($ag_vals);
        } else {
            $get_group_names = AdgroupPreview::recursiveParseAdwordsArray($ag_vals,'');
        }
        return $get_group_names;

    }


    /**
     *
     * @param ESAdwords $es_adwords
     * @param $tags
     * @param $adwords_name
     * @return array
     */
    private function prepareCampaignResult( ESAdwords $es_adwords,$tags,$adwords_name)
    {
        $ag_query = $es_adwords->esAggBuilder($tags);
        $aggegrations = $es_adwords->buildAggQuery($ag_query);
        $ag_vals = AdgroupPreview::recursivePrepareAdwordsData($aggegrations);
        $get_grouped = $this->prepareGroupNameArray($tags,$ag_vals);
        $get_campaign_names = AdgroupPreview::createAdwordsGenericName($tags,$get_grouped,$adwords_name);
        if(count($tags) > 0 ) {
            return $get_campaign_names;
        }

    }


    /**
     * Recreate the index and load the rules for adwords
     * @param $fk_adwords_feed_id
     * @param $feed_id
     */
    public function copyIndexWithRules($fk_adwords_feed_id,$feed_id)
    {
        $FeedOperationDirector = new FeedOperationDirector();
        $rule_products = $FeedOperationDirector->buildAdwordRules($fk_adwords_feed_id,[],0,$feed_id);
        $tmp_index_name =  createEsIndexName($feed_id,ESIndexTypes::TMP);
        $adwords_index_name = createEsIndexName($fk_adwords_feed_id,ESIndexTypes::ADWORDS);
        $tmp_index = new DynamicFeedRepository($tmp_index_name, DFBUILDER_ES_TYPE);
        $adwords_index = new DynamicFeedRepository($adwords_index_name, DFBUILDER_ES_TYPE);


        if($adwords_index->client->indices()->exists(['index'=>$adwords_index_name])) {
            $adwords_index->deleteIndex();
        }


        /**
         * Clone index
         */
        $tmp_feed_mapping_with_types = $tmp_index->getFeedMapping(false);
        $adwords_index->createDynamicMapping($tmp_feed_mapping_with_types);
        $inserts = [];
        foreach($tmp_index->getAllDocuments(true) as $es_data) {
            if(isset($rule_products[$es_data['_id']])) {
                $es_data['_source'] = $rule_products[$es_data['_id']]['_source'];
            }
            $inserts[] = $es_data['_source'];
            //$adwords_index->indexIntoElasticSearch($es_data['_source'],true,$es_data['_id']); // F*cking slow bro...
        }
        $adwords_index->insertBulkData($inserts,[],ESImportType::INDEX,false,true);

    }


    /**
     * @param $data
     * @param $revision_mapping
     * @param $product
     * @return mixed
     */
    public function applyUpdateRevisions($data,$revision_mapping,$product)
    {

        if(isset($revision_mapping[$product['_id']])) {
            $mappings = $revision_mapping[$product['_id']];
            for($i = 0; $i < count($mappings); $i++ ) {
                if(isset( $data[$mappings[$i]['revision_field_name']])) {
                    $data[$mappings[$i]['revision_field_name']] = $mappings[$i]['revision_new_content'];
                }
            }
        }

        return $data;

    }





    /**
     * Apply the keywords
     * @param $adwords_keywords
     * @param $product_replacements
     * @param $preview_adgroup_id
     * @param $fk_adwords_feed_id
     */
    public function applyKeywords($adwords_keywords,$product_replacements,$preview_adgroup_id,$fk_adwords_feed_id)
    {

        foreach($adwords_keywords as $keywords) {
            $formatted_keyword = strtr($keywords->keyword,$product_replacements);
            $keyword_types = json_decode($keywords->keyword_type,true);
            foreach($keyword_types as $keyword_type) {
                $has_preview_keyword = $this->adwords_keyword_preview->keywordExistsInPreview($preview_adgroup_id,$formatted_keyword,$keyword_type);
                if(is_null($has_preview_keyword)) {
                    $this->adwords_keyword_preview->createPreview(
                        [
                            'formatted_keyword'=>$formatted_keyword,
                            'fk_adwords_feed_id'=>$fk_adwords_feed_id,
                            'fk_adwords_keyword_id'=>$keywords->id,
                            'fk_adgroup_preview_id'=>$preview_adgroup_id,
                            'keyword_type'=>$keyword_type,
                        ]
                    );
                } else {
                    $this->adwords_keyword_preview->createPreview(
                        [
                            'delete_keyword'=>false,
                        ],
                        $has_preview_keyword->id
                    );
                }

            }
        }
    }

    /**
     * @param $ads
     * @param $product_replacements
     * @param $preview_campaign_id
     * @param $preview_adgroup_id
     * @param $fk_adwords_feed_id
     * @return array
     */
    public function prepareAdData($ads,$product_replacements,$preview_campaign_id,$preview_adgroup_id,$fk_adwords_feed_id,$ad_id)
    {

        return [
            'headline_1'=>strtr($ads->headline_1,$product_replacements),
            'headline_2'=>strtr($ads->headline_2,$product_replacements),
            'description'=>strtr($ads->description,$product_replacements),
            'path_1'=>strtr($ads->path_1,$product_replacements),
            'path_2'=>strtr($ads->path_2,$product_replacements),
            'final_url'=>strtr(trim($ads->final_url),$product_replacements),
            'is_valid'=>true,
            'fk_adwords_feed_id'=>$fk_adwords_feed_id,
            'fk_adgroup_preview_id'=>$preview_adgroup_id,
            'fk_campaigns_preview_id'=>$preview_campaign_id,
            'fk_adwords_ad_id'=>$ad_id
        ];

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**
         * Globa; settings
         */
        $fk_adwords_feed_id = 5;
        $get_adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
        $feed_id = $get_adwords_feed->fk_feed_id;
        $adwords_configuration = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
        $live = $adwords_configuration->live;







        /**
         * Let start the firework....
         * And see what going to happen...
         */


        $validate_ad  = new AdwordsValidator();
        $adwords_revision = new AdwordsRevisionRepository(new AdwordsRevision());
        $update_revisions = $adwords_revision->getUpdatedRevisions($fk_adwords_feed_id);
        $deleted_revision = $adwords_revision->getDeleteRevisions($fk_adwords_feed_id);
        $adwords_keywords  = $this->adwords_keyword->getKeywordsFromFeed($fk_adwords_feed_id);


        /**
         * Remove old keywords
         */
        foreach($this->adwords_keyword->getKeywordsWithNoConnectionToDelete($fk_adwords_feed_id) as $cleanup_keyword) {
            $this->adwords_keyword->removeKeyword($cleanup_keyword->keyword_id);
        }



        /**
         * If we are in preview mode, we can delete the items
         */
        if(!$live) {
            $this->ad_campaign_preview->removeAdCampaignPreview($fk_adwords_feed_id);
        }
        $this->copyIndexWithRules($fk_adwords_feed_id,$feed_id);





        /**
         * Delete the old prefilled campaign adwords id from adwords_preview_campaign where the existing_campaign = 2, but only in preview modus..
         * Because an user can change the option, and then the adwords id stays there.
         * This will cause a delete on adwords.
         */
        if($adwords_configuration->existing_campaign == 2 && $adwords_configuration->live == 0) {
            $this->ad_campaign_preview->removeExistingCampaignFromPreview($fk_adwords_feed_id,1);
            $this->ad_campaign_preview->removeExistingCampaignFromPreview($fk_adwords_feed_id,0);
        }

        if($adwords_configuration->existing_campaign == 1 && $adwords_configuration->live == 0) {
            $this->ad_campaign_preview->removeExistingCampaignFromPreview($fk_adwords_feed_id,2);
        }

        $adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
        $es_adwords = new ESAdwords(createEsIndexName($fk_adwords_feed_id,ESIndexTypes::ADWORDS),DFBUILDER_ES_TYPE);

        /**
         * Adgroups
         */
        $adgroup_tags = AdgroupPreview::grepTags($adwords_configuration->adgroup_name);
        $campaign_tags = AdgroupPreview::grepTags($adwords_configuration->campaign_name);

        $campaign_info = $this->prepareCampaignResult($es_adwords,$campaign_tags,$adwords_configuration->campaign_name);
        $preview_campaigns_array = $this->ad_campaign_preview->getPreviewCampaigns($fk_adwords_feed_id);
        $preview_adgroup_array = $this->adgroup_preview->getAdgroupData($fk_adwords_feed_id);






        /**
         * What if the campaign name doesnt contain a field?
         *
         */
        if(is_null($campaign_info) || count($campaign_info) == 0 ) {
            $campaign_info['matchQuery'] =  ['no_campaign_tags' =>[]];
        }




        /**
         * An user has added a label for the Campaign name
         * Search every thing under the campaign if the adgroup also has an label.
         * Now we need the aggegrations for the ad groups
         */



        foreach($campaign_info['matchQuery'] as $key =>  $fields) {

            /**
             * Prepare adgroup tags
             */
            if(!is_integer($key)) {
                $campaign_name = $adwords_configuration->campaign_name;
            } else {
                $campaign_name = $campaign_info['names'][$key];
            }

            /**
             * Prepare the adgroup names
             */
            $prefilled_adwords_id = 0;

            /**
             * If a campaign has been prefilled...
             * Set the adwords id
             */
            if($adwords_configuration->existing_campaign == 2) {
                $prefilled_adwords_id = $adwords_configuration->campaign_adwords_id;
            }




            if(count($adgroup_tags)  > 0 ) {
                $create_related_adgroups = $es_adwords->buildAggQueryWithCondition($fields,$es_adwords->esAggBuilder($adgroup_tags,1));
                $ag_vals = AdgroupPreview::recursivePrepareAdwordsData($create_related_adgroups['aggregations']);
                $get_group_names = $this->prepareGroupNameArray($adgroup_tags,$ag_vals);
                $create_adgroup_names = AdgroupPreview::createAdwordsGenericName($adgroup_tags,$get_group_names,$adwords_configuration->adgroup_name);
            } else {
                $create_adgroup_names['matchQuery']=  ['no_ad_groups'=>true];
            }


            /**
             * Get campaign name from the array
             * If exists in the database then unset the array so we can delete
             * otherwise create new entry.
             */
            if(isset($preview_campaigns_array[$campaign_name])) {
                $preview_campaign_id = $preview_campaigns_array[$campaign_name];
                unset($preview_campaigns_array[$campaign_name]);
            } else {

                $preview_campaign =  $this->ad_campaign_preview->createCampaignPreview([
                    'campaign_name'=>$campaign_name,
                    'fk_adwords_feed_id'=>$fk_adwords_feed_id,
                    'adwords_id'=>$prefilled_adwords_id,
                    'existing_campaign'=>$adwords_configuration->existing_campaign,

                ]);
                $preview_campaign_id = $preview_campaign->id;
            }


            /**
             * No adgroup has been found
             */

            if(!isset($create_adgroup_names['matchQuery'])) {
                continue;
            }


            /**
             * Create the adgroups
             */
            foreach(array_keys($create_adgroup_names['matchQuery'])  as $key_index) {



                /**
                 * If we have no adgroup tags.
                 * The get the default name.
                 */
                if(count($adgroup_tags)   ==  0 ) {
                    $adgroup_name = $adwords_configuration->adgroup_name;
                    $products = $es_adwords->buildAggQueryWithCondition($fields);
                } else {
                    $adgroup_name = $create_adgroup_names['names'][$key_index];
                    $merged_campaign_and_adgroup = array_merge($create_adgroup_names['matchQuery'][$key_index],$fields);
                    $products = $es_adwords->buildAggQueryWithCondition($merged_campaign_and_adgroup);

                }


                /**
                 * Create adgroup
                 */
                if(isset($preview_adgroup_array[$preview_campaign_id][$adgroup_name])) {
                    $preview_adgroup_id = $preview_adgroup_array[$preview_campaign_id][$adgroup_name];
                    unset($preview_adgroup_array[$preview_campaign_id][$adgroup_name]);
                } else {
                    $preview_adgroup = $this->adgroup_preview->createAdgroupPreview(['adgroup_name'=>$adgroup_name,'fk_adwords_feed_id'=>$fk_adwords_feed_id,'fk_campaigns_preview_id'=>$preview_campaign_id]);
                    $preview_adgroup_id = $preview_adgroup->id;
                }




                /**
                 * Create the products under the adgroup
                 * But if the adgroup doesn't have tags, just get all the products from the feed.
                 */
                $adwords_ad = $this->adwords_ad->getAdwordsAds($fk_adwords_feed_id);
                foreach($adwords_ad as $ads) {
                    $preview_ads_with_adwords = $this->ads_preview->getPreviewAdWordsOptions($preview_campaign_id,$preview_adgroup_id,$ads->id);
                    $this->adwords_keyword_preview->setKeywordAsDeleteFromUpdate($preview_adgroup_id);

                    foreach($products as $product) {

                            /**
                             * Prepare product replacements
                             */
                            $product_replacements = AdgroupPreview::changeEsFieldNames($product['_source']);

                            /**
                             *  Add to preview keyword table.
                             */
                            $this->applyKeywords($adwords_keywords,$product_replacements,$preview_adgroup_id,$fk_adwords_feed_id);





                            /**
                             * Check the deleted revisions..
                             * And if they exists in the array continue the loop
                             */

                            if(isset($deleted_revision[$product['_id']])) {
                               continue;
                            }


                            /**
                             * Get the ads and transform it.
                             * Finally insert the ads into the database
                             */
                            $data =  $this->prepareAdData($ads,$product_replacements,$preview_campaign_id,$preview_adgroup_id,$fk_adwords_feed_id,$ads->id);
                            $data = $this->applyUpdateRevisions($data,$update_revisions,$product);
                            $data['generated_id'] = $product['_id'];

                            $ad_update_hash = AdwordsHelpers::adUpdateHash($data);


                            $ad_validator = $validate_ad->validateAd($data);
                            $ad_invalid = count($ad_validator) > 0;
                            $backup_ad_success = false;






                            /**
                             * Check if the update hash is the same
                             * And the adwords ad id > 0.
                             * And the hash does not equal with eichother then do a update
                             */

                            if( count($preview_ads_with_adwords) > 0
                                && $preview_ads_with_adwords[$product['_id']]['adwords_id'] > 0
                                && $preview_ads_with_adwords[$product['_id']]['update_hash'] != $ad_update_hash ) {
                                $this->ads_preview->createAdPreview(['delete_from_adwords'=>true],$preview_ads_with_adwords[$product['_id']]['id']);
                                $ad_validator = $validate_ad->validateAd($data);
                                $this->comment("change detected! new hash = ".$ad_update_hash. " old hash = ".$preview_ads_with_adwords[$product['_id']]['update_hash']  );
                                unset($preview_ads_with_adwords[$product['_id']]);
                                $ad_invalid = count($ad_validator) > 0;

                            }


                            /**
                             * If the ad is correct with no errors
                             * And the product doesnt exists in the database.
                             * Insert the ad to the database.
                             */
                            if(!isset($preview_ads_with_adwords[$product['_id']]) && !$ad_invalid) {
                                $data['is_valid'] = true;
                                $data['generated_id'] = $product['_id'];
                                $data['update_hash'] = $ad_update_hash;
                                $this->ads_preview->createAdPreview($data);
                                unset($preview_ads_with_adwords[$product['_id']]);
                                continue;
                            }



                        /**
                             * If the product already exists in the database
                             * And the add is valid, delete from array and continue to next array item.
                             */

                            if(isset($preview_ads_with_adwords[$product['_id']]) && !$ad_invalid) {
                                if($preview_ads_with_adwords[$product['_id']] > 0 ) {
                                    $data['errors']  = null;
                                    $this->ads_preview->updateAdByMultipleId($data,$preview_ads_with_adwords[$product['_id']]['id'],$ads->id);
                                }
                                unset($preview_ads_with_adwords[$product['_id']]);
                                continue;
                            }




                            /**
                             * If the ad exists
                             * And it has errors, remove it from the database.
                             * So we can check later again
                             */
                            if(isset($preview_ads_with_adwords[$product['_id']]) && $ad_invalid) {
                                $this->ads_preview->removeAdByProductId($product['_id'],$ads->id);
                            }



                            /**
                             * Ad is invalid.
                             * Try to add the backup ads to the database
                             */
                            if( $ad_invalid ) {
                                foreach($this->adwords_ad->getBackupTemplate($ads->id) as $backup_template) {
                                    unset($backup_data);
                                    $backup_data = $this->prepareAdData($backup_template,$product_replacements,$preview_campaign_id,$preview_adgroup_id,$fk_adwords_feed_id,$ads->id);
                                    $backup_data = $this->applyUpdateRevisions($backup_data,$update_revisions,$product);
                                    if(count($validate_ad->validateAd($backup_data)) == 0 ) {
                                        $backup_data['errors'] = null;
                                        $backup_data['is_valid'] = true;
                                        $backup_data['update_hash'] = $ad_update_hash;
                                        $backup_data['generated_id'] = $product['_id'];
                                        $this->ads_preview->createAdPreview($backup_data);
                                        $backup_ad_success = true;
                                        unset($preview_ads_with_adwords[$product['_id']]);
                                        break;
                                    }
                                }
                                unset($backup_data);
                            }



                            /**
                             * If the backup ad is invalid
                             * Enter the invalid add to the database with a error msg.
                             */
                            if(!$backup_ad_success) {
                                $data['generated_id'] = $product['_id'];
                                $data['errors'] = json_encode($ad_validator);
                                $data['is_valid'] = $backup_ad_success;
                                $data['update_hash'] = $ad_update_hash;
                                $this->ads_preview->createAdPreview($data);
                            }



                        /**
                         * Finally unset the products which exists
                         * The products which are set are stil availble..
                         */
                        if(isset($preview_ads_with_adwords[$product['_id']])) {
                            unset($preview_ads_with_adwords[$product['_id']]);
                        }

                    }


                    /**
                     * Foreach loop to mark products ad deleted
                     */
                    foreach($preview_ads_with_adwords as $a) {
                        $this->ads_preview->createAdPreview(['delete_from_adwords'=>true],$a['id']);
                    }

                }

            }


            /**
             * Delete the preview keywords
             * with no adwords id
             */
            foreach($this->adwords_keyword_preview->getDeletedKeywords($fk_adwords_feed_id,0) as $preview_keywords_to_delete) {
                $this->comment("deleted ".$preview_keywords_to_delete->id);
                $this->adwords_keyword_preview->removePreviewKeyword($preview_keywords_to_delete->id);
            }
        }



        /**
         * Set a flag so we can delete those campaigns from adwords.
         */
        foreach($preview_campaigns_array as $campaign_name=>$campaign_id) {
            $this->ad_campaign_preview->createCampaignPreview(['delete_from_adwords'=>true],$campaign_id);
        }

        /**
         * Same for adgroups
         * Set a flag so we can delete the products from adwords.
         */
        foreach($preview_adgroup_array as $key=>$extracted_adgroup_array) {
            foreach ($extracted_adgroup_array as $adgroup_value => $adgroup_id) {
                $this->adgroup_preview->createAdgroupPreview(['delete_from_adwords'=>true],$adgroup_id);
            }
        }

        /**
         * Is live, send to adwords...
         */
        if($live) {
            $send_to_adwords = new SendAdsToAdwords($fk_adwords_feed_id);
            $send_to_adwords->execute();
        }


    }
}
