<?php
namespace App\DfCore\DfBs\Adwords;
use App\DfCore\DfBs\Enum\AdwordsOptions;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Log\LoggerFacade;
use Google\AdsApi\AdWords\v201609\cm\ConstantDataService;
use Google\AdsApi\AdWords\v201705\cm\Ad;
use Google\AdsApi\AdWords\v201705\cm\AdGroupAdService;
use Google\AdsApi\AdWords\v201705\cm\AdGroupAd;
use Google\AdsApi\AdWords\v201705\cm\AdGroupAdStatus;
use Google\AdsApi\AdWords\v201705\cm\AdGroupCriterion;
use Google\AdsApi\AdWords\v201705\cm\AdGroupCriterionOperation;
use Google\AdsApi\AdWords\v201705\cm\AdGroupCriterionService;
use Google\AdsApi\AdWords\v201705\cm\AdGroupService;
use Google\AdsApi\AdWords\v201705\cm\AdGroupStatus;
use Google\AdsApi\AdWords\v201705\cm\BiddableAdGroupCriterion;
use Google\AdsApi\AdWords\v201705\cm\CampaignCriterion;
use Google\AdsApi\AdWords\v201705\cm\CampaignCriterionOperation;
use Google\AdsApi\AdWords\v201705\cm\CampaignCriterionService;
use Google\AdsApi\AdWords\v201705\cm\Criterion;
use Google\AdsApi\AdWords\v201705\cm\Keyword;
use Google\AdsApi\AdWords\v201705\cm\KeywordMatchType;
use Google\AdsApi\AdWords\v201705\cm\CampaignStatus;
use Google\AdsApi\AdWords\v201705\cm\Language;
use Google\AdsApi\AdWords\v201705\cm\NegativeAdGroupCriterion;
use Google\AdsApi\AdWords\v201705\cm\Predicate;
use Google\AdsApi\AdWords\v201705\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201705\cm\Location;
use Google\AdsApi\AdWords\v201705\cm\CampaignService;
use Google\AdsApi\AdWords\v201705\cm\OrderBy;
use Google\AdsApi\AdWords\v201705\cm\Paging;
use Google\AdsApi\AdWords\v201705\cm\Selector;
use Google\AdsApi\AdWords\v201705\cm\SortOrder;
use Google\AdsApi\AdWords\v201705\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201705\cm\Campaign;
use Google\AdsApi\AdWords\v201705\cm\Operator;
use Google\AdsApi\AdWords\v201705\cm\AdGroup;
use Google\AdsApi\AdWords\v201705\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201705\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201705\cm\CpcBid;
use Google\AdsApi\AdWords\v201705\cm\Money;
use Google\AdsApi\AdWords\v201705\cm\AdvertisingChannelType;
use Google\AdsApi\AdWords\v201705\cm\BiddingStrategyType;
use Google\AdsApi\AdWords\v201705\cm\Budget;
use Google\AdsApi\AdWords\v201705\cm\BudgetBudgetDeliveryMethod;
use Google\AdsApi\AdWords\v201705\cm\BudgetOperation;
use Google\AdsApi\AdWords\v201705\cm\BudgetService;
use Google\AdsApi\AdWords\v201705\cm\ExpandedTextAd;


use LaravelGoogleAds\Services\AdWordsService;

class AdwordsApiWrapper
{


    private $adWordsService;
    private $page_limit = 999999;
    private $customer_client_id;


    /**
     * AdwordsApiWrapper constructor.
     */
    public function __construct($customer_client_id)
    {
        $this->customer_client_id = $customer_client_id;
        $this->adWordsService = new AdWordsService();
    }



    /**
     * @todo https://developers.google.com/adwords/api/docs/samples/php/targeting#get-location-criteria-by-name
     * @todo https://developers.google.com/adwords/api/docs/samples/php/targeting#get-all-targetable-languages-and-carriers
     */
    public function getLanguages()
    {
        $constantDataService = $this->adWordsService->getService(ConstantDataService::class,$this->customer_client_id);
        try {
            return $constantDataService->getLanguageCriterion();
        } catch (\Exception $e) {
            LoggerFacade::addAlert("Adwords error - with client id= ".$this->customer_client_id." message". $e->getMessage());
            return false;
        }


    }


    /**
     * @param $match_type
     * @return string
     */
    protected function formatMatchType($match_type)
    {
        $keyword_match_type = KeywordMatchType::EXACT;
        switch ($match_type) {
            case AdwordsOptions::EXACT:
                $keyword_match_type = KeywordMatchType::EXACT;
            break;

            case AdwordsOptions::PHRASE:
                $keyword_match_type = KeywordMatchType::PHRASE;
            break;

            case AdwordsOptions::BROAD:
            case AdwordsOptions::MOD_BROAD:
                $keyword_match_type = KeywordMatchType::BROAD;
            break;
        }
        return $keyword_match_type;
    }


    /**
     * @param $criterionId
     * @param $adGroupId
     * @return bool
     */
    public function removeKeyword($criterionId,$adGroupId)
    {
        $adGroupCriterionService = $this->adWordsService->getService(AdGroupCriterionService::class,$this->customer_client_id);
        // Create criterion using an existing ID. Use the base class Criterion
        // instead of Keyword to avoid having to set keyword-specific fields.
        $criterion = new Criterion();
        $criterion->setId($criterionId);

        // Create an ad group criterion.
        $adGroupCriterion = new AdGroupCriterion();
        $adGroupCriterion->setAdGroupId($adGroupId);
        $adGroupCriterion->setCriterion($criterion);

        // Create an ad group criterion operation and add it the operations list.
        $operation = new AdGroupCriterionOperation();
        $operation->setOperand($adGroupCriterion);
        $operation->setOperator(Operator::REMOVE);
        $operations = [$operation];


        try {
            // Remove criterion on the server.
            $result = $adGroupCriterionService->mutate($operations);
            $adGroupCriterion = $result->getValue()[0];
            return $adGroupCriterion->getCriterion()->getId();
        } catch (\Exception $e) {
            debug_string('cannot remove! ', $e);
            return false;
        }

    }


    /**
     * Add the keyword to an adgroup
     * @param $adgroup_id
     * @param $keyword_options
     * @param $keyword_type
     * @return bool
     */
    public function addKeyword($adgroup_id,$keyword_options,$keyword_type,$the_keyword)
    {


        $keyword_match_type = $this->formatMatchType($keyword_type);


        $adGroupCriterionService = $this->adWordsService->getService(AdGroupCriterionService::class,$this->customer_client_id);
        $operations = [];
        $keyword = new Keyword();
        $keyword->setText($the_keyword);
        $keyword->setMatchType($keyword_match_type);
        // Create biddable ad group criterion.

        /**
         * Add normal keyword
         */
        if($keyword_options->keyword_option == AdwordsOptions::NORMAL_KEYWORD) {
            $adGroupCriterion = new BiddableAdGroupCriterion();
            $adGroupCriterion->setAdGroupId($adgroup_id);
            $adGroupCriterion->setCriterion($keyword);
        }
        /**
         * Add negative keyword
         */
        if($keyword_options->keyword_option == AdwordsOptions::NEGATIVE_KEYWORD) {
            $adGroupCriterion = new NegativeAdGroupCriterion();
            $adGroupCriterion->setAdGroupId($adgroup_id);
            $adGroupCriterion->setCriterion($keyword);
        }

        $operation = new AdGroupCriterionOperation();
        $operation->setOperand($adGroupCriterion);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;
        try {
            $result = $adGroupCriterionService->mutate($operations);
            foreach ($result->getValue() as $adGroupCriterion) {
               return $adGroupCriterion->getCriterion()->getId();
            }
        }catch (\Exception $e) {

            return false;
        }




    }

    /**
     * @param $adgroup_id
     * @return bool
     */
    public function removeAdgroup($adgroup_id)
    {
        $adGroupService = $this->adWordsService->getService(AdGroupService::class,$this->customer_client_id);

        $operations = [];
        // Create ad group with REMOVED status.
        $adGroup = new AdGroup();
        $adGroup->setId($adgroup_id);
        $adGroup->setStatus(AdGroupStatus::REMOVED);

        // Create ad group operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Remove the ad group on the server.
        try {
            $result = $adGroupService->mutate($operations);
            return $result->getValue()[0];
        } catch (\Exception $e) {
            debug_string('cannot remove Adgroup ', $e);
            return false;
        }


    }


    /**
     * @param $campaign_id
     * @return bool
     */
    public function removeCampaign($campaign_id)
    {
        $campaignService = $this->adWordsService->getService(CampaignService::class,$this->customer_client_id);
        $campaign = new Campaign();
        $campaign->setId($campaign_id);
        $campaign->setStatus(CampaignStatus::REMOVED);

        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Remove the campaign on the server.
        try {
            $result = $campaignService->mutate($operations);
            return $result->getValue()[0];
        }catch (\Exception $e) {
            debug_string('cannot remove Campaign ', $e);
            return false;
        }


    }



    /**
     * @param $ad_id
     * @param $adgroup_id
     * @return bool|AdGroupAd
     */
    public function removeAd($ad_id,$adgroup_id)
    {
        $adgroupService = $this->adWordsService->getService(AdGroupAdService::class,$this->customer_client_id);

        $operations = [];
        // Create ad using an existing ID. Use the base class Ad instead of TextAd
        // to avoid having to set ad-specific fields.
        $ad = new Ad();
        $ad->setId($ad_id);
        // Create ad group ad.
        $adGroupAd = new AdGroupAd();
        $adGroupAd->setAdGroupId($adgroup_id);
        $adGroupAd->setAd($ad);

        // Create ad group ad operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroupAd);
        $operation->setOperator(Operator::REMOVE);
        $operations[] = $operation;

        // Remove the ad on the server.
        try {
            $result = $adgroupService->mutate($operations);
            $adGroupAd = $result->getValue()[0];
            return $adGroupAd;
        } catch (\Exception $e) {
            debug_string('cannot remove ad ', $e);
            return false;
        }


    }

    /**
     * @param $adgroup_id
     * @param array $data
     * @param $live_option
     * @return array
     */
    public function createAd($adgroup_id,$data = [],$live_option)
    {


        $results = [];
        $adgroupService = $this->adWordsService->getService(AdGroupAdService::class,$this->customer_client_id);

        $operations = [];
        // Create an expanded text ad.
        $expandedTextAd = new ExpandedTextAd();
        $expandedTextAd->setHeadlinePart1($data['headline_1']);
        $expandedTextAd->setHeadlinePart2($data['headline_2']);
        $expandedTextAd->setDescription($data['description']);
        $expandedTextAd->setFinalUrls($data['final_urls']);
        $expandedTextAd->setPath1($data['path_1']);
        $expandedTextAd->setPath2($data['path_2']);
        $adGroupAd = new AdGroupAd();
        $adGroupAd->setAdGroupId($adgroup_id);
        $adGroupAd->setAd($expandedTextAd);


        if($live_option == AdwordsOptions::AD_PAUSED) {
            $adGroupAd->setStatus(AdGroupAdStatus::PAUSED);
        }

        // Create ad group ad operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroupAd);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;



        try {
            $result = $adgroupService->mutate($operations);
            // Create the expanded text ads on the server and print out some information
            // for each created expanded text ad.

            foreach ($result->getValue() as $adGroupAd) {
                $results[] = $adGroupAd->getAd()->getId();
            }
            return ['status'=>true,'id'=>$results[0]];

        } catch (\Exception $e) {
            return ['status'=>false,'message'=>$e->getMessage()];
        }



    }

    /**
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#add-ad-groups
     * @param $campaign_id
     * @param $adgroup_name
     * @return mixed
     */
    public function createAdGroup($campaign_id,$adgroup_name,$max_cpc)
    {

        $adgroupService = $this->adWordsService->getService(AdGroupService::class, $this->customer_client_id);
        // Create an ad group with required and optional settings.
        $adGroup = new AdGroup();
        $adGroup->setCampaignId($campaign_id);
        $adGroup->setName($adgroup_name);


        $bid = new CpcBid();
        $money = new Money();
        $money->setMicroAmount($this->toMicroAmount($max_cpc));

        $bid->setBid($money);
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);
        $adGroup->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;
        try {
            $result =  $adgroupService->mutate($operations);
            foreach ($result->getValue() as $adGroupAd) {
                return  $adGroupAd->getId();
            }


        } catch (\Exception $e) {
            LoggerFacade::addAlert(debug_string("cannot create adgroup ".$adgroup_name,$e));
            return false;
        }


    }


    /**
     * @param $campaign_id
     */
    public function addTargetingToCampaign($campaign_id,$target_countries,$target_languages)
    {
        $campaignCriterionService = $this->adWordsService->getService(CampaignCriterionService::class, $this->customer_client_id);
        $campaignCriteria = [];
        $countries = json_decode($target_countries,true);
        $languages = json_decode($target_languages,true);


        /**
         * Add countries
         */
        if(!is_null($countries)) {
            foreach($countries as $country_id) {
                $country = new Location();
                $country->setId($country_id);
                $campaignCriteria[] =
                    new CampaignCriterion($campaign_id, null, $country);
            }
        }

        /**
         * Add languages
         */
        if(!is_null($languages)) {
            foreach($languages as $language_id) {
                $lang = new Language();
                $lang->setId($language_id);
                $campaignCriteria[] =
                    new CampaignCriterion($campaign_id, null, $lang);
            }
        }


        $operations = [];
        foreach ($campaignCriteria as $campaignCriterion) {
            $operation = new CampaignCriterionOperation();
            $operation->setOperator(Operator::ADD);
            $operation->setOperand($campaignCriterion);
            $operations[] = $operation;
        }

        try {

            if(count($operations) > 0) {
                return  $campaignCriterionService->mutate($operations);
            } else {
                return false;
            }


        } catch (\Exception $e) {
            debug_string("cannot update campaign targeting", $e);
            return false;

        }


    }


    /**
     * @param $budget
     * @return mixed
     *
     */
    private function toMicroAmount($budget)
    {
        return  $budget * 1000000;
    }

    /**
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#add-campaigns
     * Create a campaign name.
     * @param $campaign_name
     * @param int $campaign_budget
     * @return mixed
     *
     */
    public function createCampaign($campaign_name,$campaign_budget=0,$campaign_type,$ad_delivery,$live_option)
    {

        $budgetService = $this->adWordsService->getService(BudgetService::class, $this->customer_client_id);
        $campaignService = $this->adWordsService->getService(CampaignService::class, $this->customer_client_id);



        // Create the shared budget (required).
        //TODO later apart zetten de budget
        $budget = new Budget();
        $budget->setIsExplicitlyShared(false);
        $money = new Money();
        $money->setMicroAmount($this->toMicroAmount($campaign_budget));
        $budget->setAmount($money);

        switch ($ad_delivery) {
            case AdwordsOptions::AD_ACCELERATED:
                $budget->setDeliveryMethod(BudgetBudgetDeliveryMethod::ACCELERATED);
            break;

            case AdwordsOptions::AD_STANDARD:
                $budget->setDeliveryMethod(BudgetBudgetDeliveryMethod::STANDARD);
            break;
        }

        $operations = [];


        $operation = new BudgetOperation();
        $operation->setOperand($budget);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;
        $result = $budgetService->mutate($operations);
        $budget = $result->getValue()[0];
        $operations = [];
        $campaign = new Campaign();
        $campaign->setName($campaign_name);


        switch($campaign_type) {
            case AdwordsOptions::SEARCH_NETWORK:
                $campaign->setAdvertisingChannelType(AdvertisingChannelType::SEARCH);
            break;

            case AdwordsOptions::DISPLAY_NETWORK:
                $campaign->setAdvertisingChannelType(AdvertisingChannelType::DISPLAY);
            break;


        }

        $campaign->setBudget(new Budget());
        $campaign->getBudget()->setBudgetId($budget->getBudgetId());

        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType(
            BiddingStrategyType::MANUAL_CPC);
        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        if($live_option == AdwordsOptions::CAMPAIGN_PAUSED) {
            $campaign->setStatus(CampaignStatus::PAUSED);
        }


        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;
        try {
            $result =  $campaignService->mutate($operations);
            foreach ($result->getValue() as $campaign) {
                return $campaign->getId();
            }

        } catch (\Exception $e) {
            LoggerFacade::addAlert(debug_string('Cannot create campaign ',$e));
            return false;
        }



    }



    /**
     * Update the adgroup
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#update-an-ad-group
     * @param $adgroup_id
     * @param string $adgroup_name
     * @return mixed
     */
    public function updateAdgroup($adgroup_id,$adgroup_name='')
    {

        $adgroupService = $this->adWordsService->getService(AdGroupService::class,$this->customer_client_id);
        $adGroup = new AdGroup();
        $adGroup->setId($adgroup_id);
        $adGroup->setName($adgroup_name);
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;
        try {
            return $adgroupService->mutate($operations);
        } catch (\Exception $e) {
            LoggerFacade::addAlert(debug_string('Cannot update adgroup ',$e));
            return false;
        }

    }

    /**
     * Update the campaign
     * @param $campaign_id
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#update-a-campaign
     * @param string $campaign_name
     * @return mixed
     */
    public function updateCampaign($campaign_id,$campaign_name='')
    {
        $campaignService = $this->adWordsService->getService(CampaignService::class,$this->customer_client_id);
        $campaign = new Campaign();
        $campaign->setId($campaign_id);
        $campaign->setName($campaign_name);
        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;
        try {
            return $campaignService->mutate($operations);
        }  catch (\Exception $e) {

            LoggerFacade::addAlert(debug_string('Cannot Updated campaign',$e),LogStates::CRITICAL);
            return false;
        }
    }


    /**
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#get-ad-groups
     * @param $campaign_id
     * @return array
     */
    public function getAdgroup($campaign_id)
    {

        $adGroupService = $this->adWordsService->getService(AdGroupService::class,$this->customer_client_id);
        $adgroups = [];
        // Create a selector to select all ad groups for the specified campaign.
        $selector = new Selector();
        $selector->setFields(['Id', 'Name']);
        $selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        $selector->setPredicates(
            [new Predicate('CampaignId', PredicateOperator::IN, [$campaign_id])]);
        $selector->setPaging(new Paging(0, $this->page_limit));

        $totalNumEntries = 0;
        do {
            // Retrieve ad groups one page at a time, continuing to request pages
            // until all ad groups have been retrieved.
            $page = $adGroupService->get($selector);

            // Print out some information for each ad group.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $adGroup) {
                    $adgroups[$adGroup->getId()] = $adGroup->getName();
                }
            }

            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + $this->page_limit);
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return $adgroups;


    }

    /**
     * @url https://developers.google.com/adwords/api/docs/samples/php/basic-operations#get-campaigns
     * Get all the campaign ids
     * From google adwords
     * @return mixed
     */
    public function getAllCampaigns()
    {
        $campaignService = $this->adWordsService->getService(CampaignService::class, $this->customer_client_id);
        $campaign_ids = [];
        $query = 'SELECT Id, Name, Status ORDER BY Name';
        $totalNumEntries = 0;
        $offset = 0;
        try {

            do {
                $pageQuery = sprintf('%s LIMIT %d,%d', $query, $offset, $this->page_limit);
                // Make the query request.
                $page = $campaignService->query($pageQuery);

                // Display results from the query.
                if ($page->getEntries() !== null) {
                    $totalNumEntries = $page->getTotalNumEntries();
                    foreach ($page->getEntries() as $campaign) {
                        $campaign_ids[$campaign->getId()] = $campaign->getName();
                    }
                }

                // Advance the paging offset.
                $offset += $this->page_limit;
            } while ($offset < $totalNumEntries);

            return $campaign_ids;

        } catch (\Exception $e) {
            debug_string('error fetching campaigns',$e);
            return false;
        }






    }




}