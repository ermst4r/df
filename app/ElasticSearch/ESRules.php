<?php

namespace App\ElasticSearch;
use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Enum\RuleConditions;
use App\DfCore\DfBs\Log\LoggerFacade;


/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESRules extends  BaseElasticSearch
{


    /**
     * Get all the rule ids from ES.
     * @param $feed_id
     * @return array
     */
    public function getRuleIdsFromEs($feed_id)
    {

        $ids = [];
        $condition = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll' => '1m',
            '_source'=>['generated_id','rule_id'],
            'body' => [

                'query'=>[
                    'bool' => [
                        'filter'=>[
                            'term'=>['feed_id'=>$feed_id]
                        ]
                    ]
                ]
            ]
        ];

        $results = $this->client->search($condition);
        $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
        foreach($results['hits']['hits'] as $values){
            if($values['_source']['rule_id'] >  0){
                $ids[$values['_source']['generated_id']] = $values['_source']['rule_id'];
            }
        }
        foreach($scanned_results as $values){
            if($values['_source']['rule_id']  > 0){
                $ids[$values['_source']['generated_id']] = $values['_source']['rule_id'];
            }
        }
        return $ids;

    }



    /**
     * Transform the rule formatter
     * @param $rule_condition
     * @return string
     */
    public function transEsRuleQueryType($rule_condition)
    {
        switch($rule_condition) {
            case ConditionSelector::IS_EMPTY:
            case ConditionSelector::CONTAINS_MULTI:
            case ConditionSelector::CONTAIN:
            case ConditionSelector::EQUALS:
            case ConditionSelector::EQUALS_MULTI:
            case ConditionSelector::IS_REGEXP:
                return 'must';
            break;


            case ConditionSelector::IS_NOT_EMPTY:
            case ConditionSelector::NOT_EQUALS_MULTI:
            case ConditionSelector::NOT_EQUALS:
            case ConditionSelector::NOT_CONTAIN:
            case ConditionSelector::NOT_CONTAINS_MULTI:
            case ConditionSelector::NOT_REGEXP:
                return 'must_not';

            default:
                return 'must';
        }
    }

    /**
     * Textformatter
     * @param $if_json
     * @param $key_fields
     * @return null
     */
    private function formatTextPhrase($if_json,$key_fields)
    {
        $text_phrase = (isset($if_json['if_condition_textarea'][$key_fields]) ? $if_json['if_condition_textarea'][$key_fields] : null);
        $has_text_phrase = !is_null($text_phrase);
        if(!$has_text_phrase) {
            $phrase = (isset($if_json['if_condition_field'][$key_fields]) ? $if_json['if_condition_field'][$key_fields] : null) ;
        }  else {
            $phrase = $text_phrase;
        }
        return $phrase;
    }

    /**
     * Transform the IF json query to an elasticsearch QUERY
     * Finally merge the arrays from the scanned results
     * @param $if_json
     * @param $feed_id
     * @return array
     */
    public function ifJsonToESQuery($if_json,$feed_id)
    {
        $merged_results = [];
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll' => '1m'
        ];
        $condition = [
            'body' => [
                'query'=>[
                    'bool' => [
                        'filter'=>[
                            'term'=>['feed_id'=>$feed_id]
                        ]
                    ]
                ]
            ]
        ];


        $parent_key = 0;
        $previous_rule = null;
        foreach($if_json['if_main_parent'] as $child=>$parent) {

            /**
             *
             */
            if(isset($if_json['if_field']) && array_search('all',$if_json['if_field']) !== false) {
                break;
            }

            $field_name = $if_json['if_field'][$child];
            $rule_condition = (int) $if_json['exists_of_field'][$child];
            $phrase = $this->formatTextPhrase($if_json,$child);


            /**
             * Create the parent
             * First construct the first should query
             */
            if(!is_null($parent)) {

                $current_query_type = $this->transEsRuleQueryType($rule_condition);
                if($previous_rule != $current_query_type ) {

                    /**
                     * Continue building the key where we left of..
                     */
                    if(isset($condition['body']['query']['bool'][$current_query_type])) {
                        $parent_key = key($condition['body']['query']['bool'][$current_query_type]) +1;
                    } else {
                        $parent_key = 0;
                    }
                    $previous_rule = $current_query_type;
                } else {
                    $parent_key ++;
                }


                $condition['body']['query']['bool'][$this->transEsRuleQueryType($rule_condition)][$parent_key] = [
                    'bool'=>
                        [
                            'should' =>[$this->buildCondition($rule_condition,$phrase,$field_name)],
                            'minimum_should_match'=>1
                        ]
                ];



            }

            /**
             * Finally if we have an OR, construct the or to the parent query
             */
            if(is_null($parent)) {
                $condition['body']['query']['bool'][$this->transEsRuleQueryType($rule_condition)][$parent_key]['bool']['should'][] =   $this->buildCondition($rule_condition,$phrase,$field_name);
            }
        }



        /**
         * Merge
         * Search
         * scroll and change the array structure where we retrieve the id back.
         */
        $params = array_merge($params,$condition);
        try{

            $results = $this->client->search($params);
            $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
            $merged_results[] = $this->changeArrayStructure(array_merge($results['hits']['hits'],$scanned_results));
        } catch (\Exception $e) {
            $merged_results = [];
           // LoggerFacade::addAlert($e->getMessage(),LogStates::ERROR);
        }

        return $merged_results;


    }








    /**
     * Search how many rules are mapped
     * @param $feed_id
     * @param bool $has_rule_filter
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function searchRulesMapped($feed_id,$has_rule_filter = [], $offset = 0, $limit = 10)
    {
        $params = [
            'from'=>$offset,
            'size'=>$limit,
            'index' => $this->index_name,
            'type' => $this->type_name,
            'body' => [
                'query'=>[
                    'bool' => [
                        'must'=> [
                            [
                                [
                                    'match' => [
                                        'feed_id'=>$feed_id
                                    ],

                                ],
                                [
                                    'match' =>
                                        $has_rule_filter
                                ]

                            ],

                        ]
                    ]
                ]

            ]
        ];



        return $this->client->search($params);
    }


    /**
     * The condition builder to construct a query
     * @param $rule_condition
     * @param $phrase
     * @param $field_name
     * @return array
     */
    public function buildCondition($rule_condition,$phrase,$field_name)
    {
        $condition = [];
        switch($rule_condition) {
            /**
             * Contains
             */
            case ConditionSelector::CONTAIN:
                $condition = $this->buildContainQuery($phrase,$field_name);
            break;


            /**
             * Not contains
             */
            case ConditionSelector::NOT_CONTAIN:
                $condition = $this->buildContainQuery($phrase,$field_name);
            break;


            /**
             * Equals
             */
            case ConditionSelector::EQUALS:
                    $condition = $this->buildEqualsQuery($field_name,$phrase);
            break;

            /**
             * Not equals
             */
            case ConditionSelector::NOT_EQUALS:
                $condition = $this->buildEqualsQuery($field_name,$phrase);
                break;

            /**
             * empty
             */
            case ConditionSelector::IS_EMPTY:

                $condition = $this->buildEmptyQuery($field_name);
            break;

            /**
             * Not empty
             */
            case ConditionSelector::IS_NOT_EMPTY:
                $condition = $this->buildEmptyQuery($field_name);
            break;


            /**
             * Contains multi
             */
            case ConditionSelector::CONTAINS_MULTI:
                $terms = explode("\n",$phrase);
                foreach($terms as $term) {
                    $condition[] = $this->buildContainsMultiQuery($this->cleanEsQueryFields($term),$field_name);
                }

                break;

            /**
             * Not Contains multi
             */
            case ConditionSelector::NOT_CONTAINS_MULTI:
                $terms = explode("\n",$phrase);
                foreach($terms as $term) {
                    $condition[] = $this->buildContainsMultiQuery($this->cleanEsQueryFields($term),$field_name);
                }
                break;

            /**
             *  equals multi
             */
            case ConditionSelector::EQUALS_MULTI:
                $terms = explode("\n",$phrase);
                foreach($terms as $term) {
                    $condition[] = $this->buildEqualsQuery($field_name,$this->cleanEsQueryFields($term));
                }
                break;

            /**
             * Not equals multi
             */
            case ConditionSelector::NOT_EQUALS_MULTI:
                $terms = explode("\n",$phrase);
                foreach($terms as $term) {
                    $condition[] = $this->buildEqualsQuery($field_name,$this->cleanEsQueryFields($term));
                }
                break;


            /**
             * greater then
             */
            case ConditionSelector::GT:

                $condition = ['range' => $this->buildRangeQuery($field_name,$phrase,'gt')
                ];

                break;

            /**
             * greater equal then
             */
            case ConditionSelector::GT_EQ:

                $condition= ['range' => $this->buildRangeQuery($field_name,$phrase,'gte')
                ];

                break;

            /**
             * Less then
             */
            case ConditionSelector::LT:
                $condition = ['range' => $this->buildRangeQuery($field_name,$phrase,'lt')
                ];
                break;


            /**
             * Less then equal
             */
            case ConditionSelector::LT_EQ:
                $condition = ['range' => $this->buildRangeQuery($field_name,$phrase,'lte')
                ];
                break;



        }

        return $condition;
    }

}