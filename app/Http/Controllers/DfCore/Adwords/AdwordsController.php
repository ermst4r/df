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




namespace App\Http\Controllers\DfCore\Adwords;

use App\DfCore\DfBs\Adwords\AdwordsApiWrapper;
use App\DfCore\DfBs\Adwords\AdwordsHelpers;
use App\DfCore\DfBs\Enum\AdwordsOptions;
use App\DfCore\DfBs\Enum\RevisionType;
use App\DfCore\DfBs\Enum\UrlKey;
use App\DfCore\DfBs\Rules\Wizard\ChannelWizard;
use App\ElasticSearch\ESAdwords;
use App\Entity\Repository\Contract\iAdCampaignPreview;
use App\Entity\Repository\Contract\iAdsKeywordPreview;
use App\Entity\Repository\Contract\iAdsPreview;
use App\Entity\Repository\Contract\iAdwordsAd;
use App\Entity\Repository\Contract\iAdwordsConfiguration;
use App\Entity\Repository\Contract\iAdwordsfeed;
use App\Entity\Repository\Contract\iAdwordsGoogleCountries;
use App\Entity\Repository\Contract\iAdwordsGoogleLanguages;
use App\Entity\Repository\Contract\iAdwordsKeyword;
use App\Entity\Repository\Contract\iAdwordsRevision;
use App\Entity\Repository\Contract\iAdwordsTarget;
use App\Entity\Repository\Contract\iFeed;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateAdwords;
use Illuminate\Http\Request;
use Route;


/**
 * @author: Erwin Nandpersad
 * @website: http://www.ermmedia.nl
 * Just a simple idiot controller to save the different rules
 * Class AdwordsController
 * @package App\Http\Controllers\DfCore\Adwords
 */
class AdwordsController extends Controller
{


    /**
     * @var
     */
    private $url_key;

    /**
     * @var
     */
    private $route_name;

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
    private $adwords_keyword;

    /**
     * @var
     */
    private $adwords_target;

    /**
     * @var
     */
    private $ad_campaign_preview;

    /**
     * @var
     */
    private $ads_preview;

    /**
     * @var
     */
    private $adwords_feed;

    /**
     * @var
     */
    private $adwords_revision;

    /**
     * @var iAdsKeywordPreview
     */
    private $keywords_preview;



    /**
     * @var iAdwordsGoogleCountries
     */
    private $google_countries;

    /**
     * @var iAdwordsGoogleLanguages
     */
    private $adwords_google_languages;


    /**
     * @var iFeed
     */
    private $feed;
    /**
     * AdwordsController constructor.
     * @param Request $request
     * @param iAdwordsConfiguration $adwords_configuration
     * @param iAdwordsAd $adwords_ad
     * @param iAdwordsKeyword $adwords_keyword
     */
    public  function __construct(Request $request, iAdwordsConfiguration $adwords_configuration,
                                 iAdwordsAd $adwords_ad, iAdwordsKeyword $adwords_keyword, iAdwordsTarget $adwords_target,
                                 iAdCampaignPreview $ad_campaign_preview, iAdsPreview $ads_preview, iAdwordsRevision $adwords_revision,
                                 iAdwordsfeed $adwordsfeed, iAdsKeywordPreview $keywords_preview, iAdwordsGoogleCountries $google_countries,
                                iAdwordsGoogleLanguages $adwords_google_languages, iFeed $feed
                                    )
    {

        if(php_sapi_name() != 'cli') {
            $this->url_key =  (int) $request->get('url_key');
            $this->route_name = Route::currentRouteName();
        }

        $this->adwords_configuration = $adwords_configuration;
        $this->adwords_ad = $adwords_ad;
        $this->adwords_keyword = $adwords_keyword;
        $this->adwords_target = $adwords_target;
        $this->ad_campaign_preview = $ad_campaign_preview;
        $this->feed = $feed;
        $this->ads_preview = $ads_preview;
        $this->adwords_revision = $adwords_revision;
        $this->adwords_feed = $adwordsfeed;
        $this->keywords_preview = $keywords_preview;
        $this->google_countries = $google_countries;
        $this->adwords_google_languages = $adwords_google_languages;


    }




    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function start_wizard(Request $request)
    {
        $feed_id = $request->get('feed_id');
        if($feed_id > 0) {
            return redirect()->route('adwords.adwords_feed',['feed_id'=>$feed_id]);
        }

        $feeds = $this->feed->getFeedByStore(session('store_id'));
        if(count($feeds) == 0 ){
            $request->session()->flash('flash_warning_message',trans('messages.channel_start_lbl6'));
            return redirect()->route('import.selectfeed');
        }
        return view('dfcore.adwords.start_wizard')->with(compact('feeds'));
    }


    /**
     * @return $this
     */

    public function adwords_manage()
    {
        $feeds = $this->adwords_feed->getCompleteAdwordsFeeds(session('store_id'));
        return view('dfcore.adwords.adwords_manage')->with(compact('feeds'));
    }


    /**
     * @param $feed_id
     * @param int $adwords_feed_id
     * @return $this
     */
    public function adwords_feed($feed_id,$adwords_feed_id=0)
    {
        $adwords_feed  = null;
        $wizard = null;
        $route_name = $this->route_name;
        if($adwords_feed_id > 0) {
            $adwords_feed = $this->adwords_feed->getAdwordsFeed($adwords_feed_id);
            $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$adwords_feed_id]);
        }
        $adwords_feeds = $this->adwords_feed->getAdwordsFeedFromFeedId($feed_id);
        return view('dfcore.adwords.adwords_feed')->with(compact('feed_id','adwords_feed_id','adwords_feed','adwords_feeds','wizard','route_name'));

    }


    /**
     * @param Request $request
     */
    public function post_adwords_feed(Request $request)
    {
        $data = $request->only(['name','adwords_account_id','fk_feed_id','update_interval','active']);


        if((int) $request->get('id') == 0 ) {
            $data['next_update'] = \Carbon\Carbon::now()->tz(DFBULDER_TIMEZONE)->addSecond($data['update_interval']);
        }
        $create_adwords = $this->adwords_feed->createAdwordsFeed($data,(int) $request->get('id'));

        if((int) $request->get('id') == 0 ) {
            $id = $create_adwords->id;

            $this->adwords_configuration->createAdwordsConfiguration(['fk_adwords_feed_id'=>$create_adwords->id,'campaign_name'=>'#Campaign 1','adgroup_name'=>'#Adgroup 1','cpc'=>1.00,'daily_budget'=>1.00]);
        } else {
            $id = $create_adwords;
        }

        $request->session()->flash('flash_success_noty',trans('messages.adwords_feed_lbl4'));
        return redirect()->route('adwords.adwords_settings',['feed_id'=>$data['fk_feed_id'],'fk_adwords_feed_id'=>$id]);
    }



    public function remove_adwords_feed($id,$feed_id)
    {
        $this->adwords_feed->removeAdwordsFeed($id);
        return redirect()->route('adwords.adwords_feed',['feed_id'=>$feed_id]);

    }



    /**
     * Get the existing campaigns from adwords
     * @param Request $request
     */
    public function ajax_get_campaigns(Request $request)
    {
        $fk_adwords_feed_id = $request->get('fk_adwords_feed_id');
        $adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
        $adwords_api_wrapper = new AdwordsApiWrapper($adwords_feed->adwords_account_id);
        return \Illuminate\Support\Facades\Response::json($adwords_api_wrapper->getAllCampaigns());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ajax_adwords_revision(Request $request)
    {


        $data_object = json_decode($request->get('data_object'),true);
        $revision_type = (int) $request->get('type');
        $fk_adwords_feed_id = (int) $request->get('fk_adwords_feed_id');

        foreach($data_object as $data) {
            switch ($revision_type) {
            case RevisionType::UPDATE:
                $fk_ads_preview_id = $data['fk_ads_preview_id'];
                $ads_preview = $this->ads_preview->getAd($fk_ads_preview_id);
                $data['generated_id'] = $ads_preview->generated_id;

                $revision_type = $data['revision_type'];
                $this->adwords_revision->addRevision(
                    $data);
                $this->ads_preview->createAdPreview(
                    [
                        $data['revision_field_name']=>$data['revision_new_content']
                    ],
                    $data['fk_ads_preview_id']
                );

                /**
                 * Only update the hash directly when the ad isn't send to adwords...
                 */
                if($ads_preview->adwords_id == 0 ) {

                    $updated_ad = $this->ads_preview->getAd($data['fk_ads_preview_id']);
                    $update_hash = AdwordsHelpers::adUpdateHash(
                        [
                            'headline_1'=>$updated_ad->headline_1,
                            'headline_2'=>$updated_ad->headline_2,
                            'description'=>$updated_ad->description,
                            'path_1'=>$updated_ad->path_1,
                            'path_2'=>$updated_ad->path_2,
                            'final_url'=>$updated_ad->final_url,
                            'generated_id'=>$updated_ad->generated_id
                        ]);
                    $this->ads_preview->createAdPreview(['update_hash'=>$update_hash],$data['fk_ads_preview_id']);
                }



            break;

            case RevisionType::DELETE:
                $ads_preview = $this->ads_preview->getAd($data);
                $insertData = [
                    'fk_adwords_feed_id'=>$fk_adwords_feed_id,
                    'fk_ads_preview_id'=>0,
                    'generated_id'=>$ads_preview->generated_id,
                    'revision_type'=>RevisionType::DELETE,
                    'revision_field_name'=>null,
                    'revision_new_content'=>null,
                ];
                $this->adwords_revision->addRevision($insertData);

                if($ads_preview->adwords_id == 0 ) {
                    $this->ads_preview->removeAdPreview($data);
                } else {
                    $this->ads_preview->createAdPreview(['delete_from_adwords'=>true],$ads_preview->id);
                }

            break;


        }

        }
        return \Illuminate\Support\Facades\Response::json(true);

    }

    /**
     * @param Request $request
     */
    public function ajax_adwords_spreadsheet_hot(Request $request)
    {
        $fk_adwords_feed_id = (int) $request->get('fk_adwords_feed_id');
        $fk_campaigns_preview_id = (int) $request->get('fk_campaigns_preview_id');
        $fk_adgroup_preview_id = (int) $request->get('fk_adgroup_preview_id');


        $preview_ads = $this->ads_preview->getHotPreviewAds($fk_adwords_feed_id,$fk_campaigns_preview_id,$fk_adgroup_preview_id);
        return \Illuminate\Support\Facades\Response::json($preview_ads);
    }



    /**
     * @param $fk_adwords_feed_id
     * @param $feed_id
     */
    public function adwords_spreadsheet_modus($feed_id,$fk_adwords_feed_id)
    {
        $route_name = $this->route_name;
        $count_preview_ads = $this->ads_preview->countPreviewAds($fk_adwords_feed_id);
        $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);
        return view('dfcore.adwords.spreadsheet_modus')->with(compact('fk_adwords_feed_id','feed_id','wizard','route_name','count_preview_ads'));
    }



    /**
     * @param Request $request
     */
    public function preview_ad(Request $request)
    {


        $feed_id = $request->get('feed_id');
        $fk_adwords_feed_id = $request->get('fk_adwords_feed_id');
        $adwords_configuration = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
        $this->adwords_feed->createAdwordsFeed(['updating'=>true],$fk_adwords_feed_id);
        $is_preview = ($adwords_configuration->live_option == AdwordsOptions::CAMPAIGN_PAUSED || $adwords_configuration->live_option == AdwordsOptions::AD_PAUSED || $adwords_configuration->live_option == AdwordsOptions::ALL_LIVE ? false : true);

        dispatch((new UpdateAdwords($fk_adwords_feed_id,$feed_id,true,$is_preview))->onQueue('medium'));
        $request->session()->flash('flash_success_noty',trans('messages.adwords_preview_lbl1'));
        return redirect()->route('adwords.adwords_preview',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);


    }

    /**
     * @param $fk_adwords_feed_id
     * @param $fk_campaigns_preview_id
     * @param $fk_adgroup_preview_id
     * @return $this
     */
    public function adwords_preview_products($fk_adwords_feed_id,$fk_campaigns_preview_id,$fk_adgroup_preview_id)
    {
        $route_name = $this->route_name;
        $adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
        $adwords_configuration = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
        $feed_id = $adwords_feed->fk_feed_id;

        $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);
        $ads = $this->ads_preview->getAdsFromCampaignAndAdgroup($fk_campaigns_preview_id,$fk_adgroup_preview_id);
        $ads_errors = $this->ads_preview->getAdsApiErrors($fk_campaigns_preview_id,$fk_adgroup_preview_id);
        return view('dfcore.adwords.adwords_preview_products')->with(compact('fk_adwords_feed_id','ads','feed_id','wizard',
            'route_name','fk_adgroup_preview_id','fk_campaigns_preview_id','adwords_configuration','ads_errors','adwords_feed'));

    }

    /**
     * @param $feed_id
     * @param $adwords_feed_id
     * @param Request $request
     */
    public function adwords_preview($feed_id,$fk_adwords_feed_id,Request $request)
    {
        $route_name = $this->route_name;
        $ad_campaigns = $this->ad_campaign_preview->getAdgroups($fk_adwords_feed_id);
        $adwords_configuration = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
        $adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);

        $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);
        return view('dfcore.adwords.adwords_preview')->with(compact('fk_adwords_feed_id','feed_id','route_name','fk_adwords_feed_id','wizard','ad_campaigns','adwords_configuration','adwords_feed'));
    }


    /**
     * @param $feed_id
     */
    public function adwords_settings($feed_id,$fk_adwords_feed_id=0, Request $request)
    {

        $open_collapable = $request->get('open_collapsable');
        $number_of_ads = 1;
        $number_of_keywords = 0;
        $number_of_neg_keywords = 0;
        $adwords_configuration = null;
        $adwords_ads = null;
        $adwords_keywords = null;
        $adwords_negative_keywords = null;
        $adwords_target = null;
        $adwords_feed = null;
        $route_name = $this->route_name;
        $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>0]);

        if($fk_adwords_feed_id > 0) {
            $adwords_configuration = $this->adwords_configuration->getAdwordsConfiguration($fk_adwords_feed_id);
            $adwords_feed = $this->adwords_feed->getAdwordsFeed($fk_adwords_feed_id);
            $adwords_ads = $this->adwords_ad->getAdwordsAds($fk_adwords_feed_id);
            $number_of_ads = count($adwords_ads) == 0 ? 1 : count($adwords_ads);
            $adwords_keywords = $this->adwords_keyword->getKeyword($fk_adwords_feed_id,AdwordsOptions::NORMAL_KEYWORD);
            $adwords_negative_keywords = $this->adwords_keyword->getKeyword($fk_adwords_feed_id,AdwordsOptions::NEGATIVE_KEYWORD);
            $number_of_keywords = count($adwords_keywords);
            $number_of_neg_keywords = count($adwords_negative_keywords);
            $adwords_target = $this->adwords_target->getAdwordsTarget($fk_adwords_feed_id);
            $wizard = ChannelWizard::getNavigation(UrlKey::ADWORDS,['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);

        }
        $target_languages = $this->adwords_google_languages->getLanguages();
        $target_countries = $this->google_countries->getCountries();




        return view('dfcore.adwords.adwords_settings')->with(compact('adwords_target','adwords_keywords','adwords_negative_keywords',
                                                                                    'adwords_ads','adwords_configuration','feed_id','number_of_ads', 'open_collapable',
                                                                                    'number_of_keywords','number_of_neg_keywords','fk_adwords_feed_id','wizard','route_name',
                                                                                     'target_countries','target_languages','adwords_feed'
        ));
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function adwords_es_fields(Request $request)
    {
        $feed_id = (int) $request->get('feed_id');
        $index_name = createEsIndexName($feed_id);
        $es_adwords = new ESAdwords($index_name,DFBUILDER_ES_TYPE);
        return \Illuminate\Support\Facades\Response::json($es_adwords->getEsFields($feed_id));
    }


    /**
     * @param Request $request
     */
    public function ajax_adwords_add($feed_id,Request $request)
    {
        $item_id = (int) $request->get('item_id');
        return view('dfcore.adwords.partial.adwords_ad_form')->with(compact('feed_id','item_id'));
    }

    /**
     * @param $feed_id
     * @param Request $request
     */
    public function ajax_keywords_add($feed_id,Request $request)
    {
        $item_id = (int) $request->get('item_id');
        return view('dfcore.adwords.partial.keyword_add')->with(compact('feed_id','item_id'));

    }


    /**
     *
     * Remove different functions from the ads
     * @param $feed_id
     * @param Request $request
     */
    public function ajax_remove_adwords_items(Request $request)
    {
        $type = $request->get('type');
        $id = (int) $request->get('id');
        $backup_ad = (int) $request->get('backup_ad');
        switch($type) {

            case 'keyword':
            case 'neg_keyword':
                $this->keywords_preview->setKeywordDeletedFromKeyword($id);
                $this->adwords_keyword->createKeyword(['visible'=>false],$id);
            break;

            case 'ad':
                if($backup_ad == 0) {

                    $this->adwords_ad->removeParentAd($id);
                }
                $this->adwords_ad->removeAd($id);
            break;
        }

        return \Illuminate\Support\Facades\Response::json(true);
    }



    /**
     * @param $feed_id
     * @param Request $request
     * @return $this
     */
    public function ajax_keywords_negative($feed_id,Request $request)
    {
        $item_id = (int) $request->get('item_id');
        return view('dfcore.adwords.partial.keyword_negative_add')->with(compact('feed_id','item_id'));

    }


    /**
     * @param $feed_id
     * @param $fk_adwords_feed_id
     * @param $parent_id
     */
    public function backup_ads($feed_id,$fk_adwords_feed_id,$parent_id)
    {

        $adwords_ads = $this->adwords_ad->getBackupTemplate($parent_id);
        $parent_ad = $this->adwords_ad->getAd($parent_id);
        $number_of_ads = (count($adwords_ads) == 0 ? 1 : count($adwords_ads));
        return view('dfcore.adwords.backup_ads')->with(compact('feed_id','fk_adwords_feed_id','parent_id','adwords_ads','number_of_ads','parent_ad'));

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_adwords_backup(Request $request)
    {
        $adwords_ad_id =  $request->get('adwords_ad_id');

        $headline_1 = $request->get('headline_1');
        $headline_2 = $request->get('headline_2');
        $description = $request->get('description');
        $path_1 = $request->get('path_1');
        $path_2 = $request->get('path_2');
        $final_url = $request->get('final_url');
        $fk_adwords_feed_id = (int) $request->get('fk_adwords_feed_id');
        $parent_id = (int) $request->get('parent_id');
        $feed_id = (int) $request->get('feed_id');
        $ads = [];
        /**
         * Create the ads
         */
        if(!is_null($headline_1)) {

            foreach($headline_1 as $key => $ad_values) {
                if(!is_null($ad_values)) {
                    $id = (int) $adwords_ad_id[$key];
                    $ads['headline_1'] =  nbsp_to_space($headline_1[$key]);
                    $ads['headline_2'] =  nbsp_to_space($headline_2[$key]);
                    $ads['description'] =  nbsp_to_space($description[$key]);
                    $ads['path_1'] =  nbsp_to_space($path_1[$key]);
                    $ads['path_2'] =  nbsp_to_space($path_2[$key]);
                    $ads['final_url'] =  nbsp_to_space($final_url[$key]);
                    $ads['fk_adwords_feed_id'] =$fk_adwords_feed_id;
                    $ads['is_backup_template'] = true;
                    $ads['parent_id'] =   $parent_id;
                    if(!empty($ads['headline_1']) &&  !empty($ads['headline_2']) && !empty($ads['description'])  && !empty($ads['final_url']) ) {
                        $this->adwords_ad->createAds($ads,$id);
                    }
                }

            }
        }


        $request->session()->flash('flash_success_noty',trans('messages.adwords_backup_lbl8'));
        if($request->get('save_next')) {

            return redirect()->route('adwords.adwords_settings',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);
        } else {
            return redirect()->route('adwords.backup_ads',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id,'parent'=>$parent_id]);
        }



    }



    /**
     * @param Request $request
     */
    public function post_adwords_settings(Request $request)
    {



        $existing_campaign = (int) $request->get('existing_campaign');
        $feed_id = (int) $request->get('feed_id');
        $adwords_configuration = $request->only(['cpc','daily_budget']);
        $adwords_configuration['campaign_name'] = nbsp_to_space($request->get('campaign_name'));
        $adwords_configuration['adgroup_name'] = nbsp_to_space($request->get('adgroup_name'));
        $adwords_configuration['cpc'] = str_replace(",",".",$request->get('cpc'));
        $adwords_configuration['existing_campaign'] = $existing_campaign;
        $adwords_configuration['live_option'] = $request->get('live_option');
        $adwords_configuration['live'] = true;
        if($adwords_configuration['live_option'] == AdwordsOptions::PREVIEW_MODUS) {
            $adwords_configuration['live'] = false;
        }


        $adwords_configuration['campaign_adwords_id']  = 0;
        if($existing_campaign == 2 ) {
            $adwords_configuration['campaign_adwords_id'] = (int)$request->get('prefilled_campaign');
        }

        $adwords_configuration['daily_budget'] = str_replace(",",".",$request->get('daily_budget'));
        $fk_adwords_feed_id = (int) $request->get('fk_adwords_feed_id');
        $adwords_configuration_id = (int) $request->get('adwords_configuration_id');
        $adwords_configuration['fk_adwords_feed_id'] = $fk_adwords_feed_id;

        // Add the basic adwords configuration
        if(!is_null($adwords_configuration['live_option'])) {
            $this->adwords_configuration->createAdwordsConfiguration($adwords_configuration,$adwords_configuration_id);
        }



        // add the adds
        $adwords_ad_id =  $request->get('adwords_ad_id');

        $headline_1 = $request->get('headline_1');
        $headline_2 = $request->get('headline_2');
        $description = $request->get('description');
        $path_1 = $request->get('path_1');
        $path_2 = $request->get('path_2');
        $final_url = $request->get('final_url');
        $ads = [];


        /**
         * Create the ads
         */
       if(!is_null($headline_1)) {
           foreach($headline_1 as $key => $ad_values) {
               if(!is_null($ad_values)) {
                   $id = (int) $adwords_ad_id[$key];
                   $ads['headline_1'] =  nbsp_to_space(strip_tags($headline_1[$key]));
                   $ads['headline_2'] =  nbsp_to_space(strip_tags($headline_2[$key]));
                   $ads['description'] =  nbsp_to_space(strip_tags($description[$key]));
                   $ads['path_1'] =  nbsp_to_space(strip_tags($path_1[$key]));
                   $ads['path_2'] =  nbsp_to_space(strip_tags($path_2[$key]));
                   $ads['final_url'] =  nbsp_to_space(strip_tags($final_url[$key]));
                   $ads['fk_adwords_feed_id'] =$fk_adwords_feed_id;
                   if(!empty($ads['headline_1']) &&  !empty($ads['headline_2']) && !empty($ads['description']) && !empty($ads['final_url']) ) {
                       $this->adwords_ad->createAds($ads,$id);
                   }
               }

           }

       }



        /**
         * Add the keywords
         */
        $keyword_option = $request->get('keyword_option'); // negative or normal
        $keyword = $request->get('keyword'); // negative or normal
        $keyword_type = $request->get('keyword_type');
        $keyword_id = $request->get('keyword_id');
        $keywords_array = [];
        if(!is_null($keyword)) {
            foreach($keyword as $key=>$k_values) {
                $id = (int)  $keyword_id[$key];
                $keywords_array['keyword'] = nbsp_to_space($keyword[$key]);
                $keywords_array['keyword_type'] = ( isset($keyword_type[$key]) && !is_null($keyword_type[$key])   ? json_encode($keyword_type[$key]) : '') ;
                $keywords_array['keyword_option'] = $keyword_option[$key];
                $keywords_array['fk_adwords_feed_id'] = $fk_adwords_feed_id;
                if(!empty($keywords_array['keyword'])) {
                    $this->adwords_keyword->createKeyword($keywords_array,$id);
                }

            }
        }

        /**
         * Add te negative keywords
         */
        $keywords_array = [];
        $keyword_option = $request->get('keyword_option_negative'); // negative or normal
        $keyword_negative = $request->get('keyword_negative'); // negative or normal
        $keyword_type = $request->get('keyword_type_negative');
        $keyword_id = $request->get('keyword_id_negative');

        if(!is_null($keyword_negative)) {
            foreach($keyword_negative as $key=>$k_values) {
                $id = (int)  $keyword_id[$key];
                $keywords_array['keyword'] = nbsp_to_space($keyword_negative[$key]);
                $keywords_array['keyword_type'] = ( isset($keyword_type[$key]) && !is_null($keyword_type[$key])   ? json_encode($keyword_type[$key]) : '') ;
                $keywords_array['keyword_option'] = $keyword_option[$key];
                $keywords_array['fk_adwords_feed_id'] = $fk_adwords_feed_id;
                if(!empty($keywords_array['keyword'])) {
                    $this->adwords_keyword->createKeyword($keywords_array,$id);
                }
            }
        }

        /**
         * Add campaign targeting..
         */
        $target_countries = $request->get('target_countries');
        $target_languages = $request->get('target_languages');
        $target_array = $request->only(['campaign_type','ad_delivery']);
        $adwords_target_id = (int) $request->get('adwords_target_id');
        $target_array['target_countries'] = ( isset($target_countries) && !is_null($target_countries)   ? json_encode($target_countries) : '');
        $target_array['target_languages'] = ( isset($target_languages) && !is_null($target_languages)   ? json_encode($target_languages) : '');
        $target_array['fk_adwords_feed_id'] = $fk_adwords_feed_id;
        if(!is_null($adwords_configuration['live_option'])) {
            $this->adwords_target->createAdwordsTarget($target_array, $adwords_target_id);
        }
        $request->session()->flash('flash_success_noty',trans('messages.adwords_preview_lbl23'));
        return redirect()->route('adwords.adwords_settings',['feed_id'=>$feed_id,'fk_adwords_feed_id'=>$fk_adwords_feed_id]);

    }





}