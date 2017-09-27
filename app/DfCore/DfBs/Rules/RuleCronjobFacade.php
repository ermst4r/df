<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 06-08-17
 * Time: 14:50
 */

namespace App\DfCore\DfBs\Rules;


use App\DfCore\DfBs\Enum\UrlKey;
use App\ElasticSearch\ESCategorizeFilter;
use App\ElasticSearch\ESRules;
use App\Entity\Adwordsfeed;
use App\Entity\Bolfeed;
use App\Entity\CategoryFilter;
use App\Entity\ChannelFeed;
use App\Entity\Repository\AdwordsfeedRepository;
use App\Entity\Repository\BolFeedRepository;
use App\Entity\Repository\CategoryFilterRepository;
use App\Entity\Repository\ChannelFeedRepository;
use App\Entity\Repository\RuleConditionRepository;
use App\Entity\Repository\RuleRepository;
use App\Entity\Rule;
use App\Entity\RuleCondition;

/**
 * A simple wrapper for updating the counter
 * Register in this class the rules and filter counters...
 * Class RuleCronjobFacade
 * @package App\DfCore\DfBs\Rules
 */
class RuleCronjobFacade
{






    /**
     * @param $category_filter_id
     * @param $component_identifier
     * @param $url_key
     * @param bool $refresh
     */
    public static function insertFilters($category_filter_id,$component_identifier,$url_key,$get_category_filter,$condition,$refresh=false)
    {
        $es_cat_field_name = es_cat_field_name($component_identifier,$url_key);
        $index_name = createEsIndexName($get_category_filter->fk_feed_id);
        $es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);
        $get_results = $es_feed->categorizeSearchOperations($get_category_filter->fk_feed_id,$get_category_filter->phrase,$get_category_filter->field,
            $condition,0,DFBUILDER_DEFAULT_ES_LIMIT,'1m');
        $scanned_results = $es_feed->scrollThroughResults($get_results['_scroll_id'],'1m');


        /**
         * Fetch first scan
         */
        foreach($get_results['hits'] as $first_hits) {
            if(is_array($first_hits)) {
                for($i=0; $i<count($first_hits); $i++) {
                    $prev_cat_ids = (isset($first_hits[$i]['_source']['cat_ids']) ? $first_hits[$i]['_source']['cat_ids'] : []) ;

                    if(!in_array($category_filter_id,$prev_cat_ids)) {
                        array_push($prev_cat_ids,$category_filter_id);
                    }

                    $es_feed->updateDocument($first_hits[$i]['_id'],
                        ['category_filters' => [$es_cat_field_name=>true],
                            'cat_ids'=>$prev_cat_ids
                        ],$refresh);
                }
            }
        }


        /**
         * Fetch second scan...
         */
        $teller = 0;
        foreach ($scanned_results as $s) {
            $prev_cat_ids = (isset($s['_source']['cat_ids']) ? $s['_source']['cat_ids'] : []) ;
            array_push($prev_cat_ids,$category_filter_id);
            $es_feed->updateDocument($s['_id'],
                ['category_filters' => [$es_cat_field_name=>true],
                    'cat_ids'=>$prev_cat_ids
                ],$refresh);

            $teller++;
        }
    }

    /**
     * @param $get_rule
     * @param $url_key
     * @param $component_identifier
     * @param $rule_id
     */
    public static function insertTmpRules($get_rule,$url_key,$component_identifier,$rule_id,$refresh=false)
    {

        $ruleCondition = new RuleConditionRepository( new RuleCondition());
        $feed_id = $get_rule->fk_feed_id;
        $es_rules = new ESRules(createEsIndexName($get_rule->fk_feed_id),DFBUILDER_ES_TYPE);
        $rule_condition = $ruleCondition->getRuleCondition($rule_id);

        $index_name = createEsIndexName($feed_id);
        $es_feed = new ESCategorizeFilter($index_name,DFBUILDER_ES_TYPE);
        $es_cat_field_name = es_cat_field_name($component_identifier,$url_key);
        if(count($rule_condition) > 0  ) {
            $get_condition = json_decode($ruleCondition->getRuleCondition($rule_id)[0], true);

            $get_condition = $get_condition['rules'];
            $products = $es_rules->ifJsonToESQuery($get_condition, $feed_id);

            for ($i = 0; $i < count($products); $i++) {
                foreach ($products[$i] as $generated_id => $product) {
                    $prev_rule_ids = (isset($product['_source']['rule_ids']) ? $product['_source']['rule_ids'] : []) ;

                    if(!in_array($rule_id,$prev_rule_ids)) {
                        array_push($prev_rule_ids,$rule_id);
                    }

                    $es_feed->updateDocument($product['_id'],
                        [
                            'rule_filters' => [$es_cat_field_name=>true],
                            'rule_ids'=>$prev_rule_ids
                        ],$refresh);
                }
            }
        }
    }


    /**
     * Register over here the category filter counters
     * @param $feed_id
     */
    public static function updateAllFiltersFacade($feed_id)
    {

        /**
         * @Registered Channel filter counter update
         */
        $categoryfilter = new CategoryFilterRepository(new CategoryFilter());
        $channel_categories = $categoryfilter->getChannelCategories($feed_id,true);
        foreach($channel_categories as $filter) {
            RuleCronjobFacade::insertFilters($filter->id,$filter->fk_channel_feed_id,UrlKey::CHANNEL_FEED,$filter,$filter->category_condition,true);
        }


        /**
         * @regjsterd bol.com filter counter update
         */
        $bol_categories = $categoryfilter->getBolCategories($feed_id,true);
        foreach($bol_categories as $filter) {
            RuleCronjobFacade::insertFilters($filter->id,$filter->fk_bol_id,UrlKey::BOL,$filter,$filter->category_condition,true);
        };


    }

    /**
     * Register all rule counters over here...
     * @param $feed_id
     */
    public static function updateAllRulesFacade($feed_id)
    {


        /**
         * @Registered Channel rule counter update
         */
        $channel_feed = new ChannelFeedRepository(new ChannelFeed());
        $rules = new RuleRepository(new Rule());
        $channels = $channel_feed->getActiveChannels(true,$feed_id);
        foreach($channels as $channel) {
            $channel_feed_id = $channel->id;
            $channel_type_id = $channel->fk_channel_type_id;
            foreach($rules->getChannelOrdersRules($channel_feed_id,$channel_type_id) as $rule){
                RuleCronjobFacade::insertTmpRules($rule,UrlKey::CHANNEL_FEED,$channel_feed_id,$rule->rule_id,true);
            }
        }


        /**
         * @Registered Adwords rule counter update
         */
        $adwords_feed_repository = new AdwordsfeedRepository(new Adwordsfeed());
        $adwords_feed =  $adwords_feed_repository->getAdwordsFeedFromFeedId($feed_id);
        foreach($adwords_feed as $adwords) {

            foreach($rules->getAdwordsOrderRules($adwords->id) as $rule){
                RuleCronjobFacade::insertTmpRules($rule,UrlKey::ADWORDS,$adwords->id,$rule->rule_id,true);
            }
        }



        /**
         * @Registered Bol.com rule counter update
         */
        $bol_repository = new BolFeedRepository(new Bolfeed());
        $bol_feed =  $bol_repository->getBolFeed($feed_id,true);
        foreach($bol_feed as $bol) {
            foreach($rules->getBolOrderdRules($bol->id) as $rule){
                RuleCronjobFacade::insertTmpRules($rule,UrlKey::BOL,$bol->id,$rule->rule_id,true);
            }
        }


    }

}