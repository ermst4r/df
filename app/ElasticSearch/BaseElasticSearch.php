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



namespace App\ElasticSearch;

use App\DfCore\DfBs\Enum\ConditionSelector;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Import\Mapping\DetectFieldType;
use App\DfCore\DfBs\Log\DfbuilderLogger;
use App\DfCore\DfBs\Log\LoggerFacade;
use Elasticsearch;
use function MongoDB\is_string_array;

/**
 * Abstract class with common ES commands
 * Class BaseElasticSearch
 * @package App\Entity\ElasticSearch
 */
abstract class BaseElasticSearch extends BaseESQuery
{

    protected $index_name;
    protected $type_name;
    public $client;
    protected $logger ;

    /**
     * DynamicFeedRepository constructor.
     * @param $index_name
     * @param $type_name
     */
    public function __construct($index_name,$type_name)
    {
        $this->index_name  = $index_name;
        $this->type_name  = $type_name;
        $this->client = Elasticsearch\ClientBuilder::create()->build();
        $this->logger  = new DfbuilderLogger();

    }


    /**
     * @param bool $change_array_structure
     * @return array|mixed
     */
    public function getAllDocuments($change_array_structure=false)
    {

        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll'=>'1m'
        ];
        $results =   $this->client->search($params);
        $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
        if($change_array_structure) {
            $merged_results = $this->changeArrayStructure(array_merge($results['hits']['hits'],$scanned_results));
        } else {
            $merged_results = array_merge($results['hits']['hits'],$scanned_results);
        }

        return $merged_results;

    }


    /**
     * @param $new_index
     */
    public function copyIndex($new_index)
    {
        $tmp_feed_mapping_with_types = $this->getFeedMapping(false);
        $new_index->createDynamicMapping($tmp_feed_mapping_with_types);
        foreach($this->getAllDocuments(true) as $es_data) {
            $new_index->indexIntoElasticSearch($es_data['_source'],true,$es_data['_id']);
        }
    }
    /**
     * @param $row
     * @param array $channel_headers
     * @return mixed
     */
    public function dispatchNewHeaders($row,$channel_headers = [])
    {



        if(count($channel_headers) > 0 ) {
            foreach(array_keys($row) as $keys) {
                foreach(array_keys($row[$keys]['_source']) as $row_keys ) {
                    $new_key = array_search($row_keys,$channel_headers);
                    if($new_key !== false ) {
                        $row[$keys]['_source'][$new_key]= $row[$keys]['_source'][$row_keys];
                       // unset($row[$keys]['_source'][$row_keys]);
                    }
                }
            }
        }

        return $row;
    }


    /**
     * Delete an index
     */
    public function deleteIndex()
    {
        $deleteParams['index'] = $this->index_name;
        if($this->client->indices()->exists(['index'=>$this->index_name])) {
            return $this->client->indices()->delete($deleteParams);
        } else {
            return false;
        }

    }


    /**
     * Remove all the data from the index
     * @param string $scroll
     * @return bool
     */
    public function deleteAllDocuments($scroll='1m')
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll' => $scroll
        ];
        try {
            $results = $this->client->search($params);
            $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
            $merged_results[] = array_merge($results['hits']['hits'],$scanned_results);
            $ids = [];
            foreach($merged_results[0] as $values) {
                $ids[] = $values['_id'];
            }
            $this->removeBulkData($ids);
        } catch (\Exception $e) {
            //LoggerFacade::addAlert($e->getMessage());
            return false;
        }


    }

    /**
     * Get the exact feed mapping with corresponding feeds, what we can insert into the dynamic mapping
     * @return array
     */
    public function getFeedMapping($exclude_fields = true)
    {

        $feed_mapping = [];
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name
        ];
        $fields_to_exclude = [];
        if($exclude_fields) {
            $fields_to_exclude = config('dfbuilder.es_live_to_exclude');
        }



        $mapping =  $this->client->indices()->getMapping($params);
        foreach ($mapping[$params['index']]['mappings'][$this->type_name]['properties'] as $m_key=>$m_value) {
            if(!in_array($m_key,$fields_to_exclude) ) {
                if(isset($m_value['type'])) {
                    $feed_mapping[$m_key] = $m_value['type'];
                }

            }

        }
        return $feed_mapping;
    }






    /**
     * Change the array structure, so that the generated_id is the array key
     * @param $array
     * @param array $glued_array
     * @param array $new_feed_values
     * @param bool $remove_es_meta_fields
     * @return mixed
     */
    public function changeArrayStructure($array,$glued_array=[], $new_feed_values = [], $remove_es_meta_fields = false)
    {

        $new_array = [];
        foreach($array as $key=>$values) {
            $new_key = $values['_source']['generated_id'];



            $new_array[$new_key] = $array[$key];


            /**
             * Remove ES META fields
             */
            if($remove_es_meta_fields ) {
                foreach(config('dfbuilder.es_live_to_exclude') as $exclude) {
                    if(isset($new_array[$new_key]['_source'][$exclude])) {
                        unset($new_array[$new_key]['_source'][$exclude]);
                    }
                }

            }



            /**
             * Do we want to add extra values?
             * Add the to the array
             */
            if(count($glued_array) >0) {
                $new_array[$new_key]['_source'] = array_merge($new_array[$new_key]['_source'],$glued_array);
            }

            /**
             * Do we want to change field values?
             * Replace from the array
             */

            if(count($new_feed_values) > 0) {
                foreach($new_feed_values as $field => $field_value) {
                    if(isset($new_array[$new_key]['_source'][$field])) {


                        $new_array[$new_key]['_source'][$field] = $field_value;

                    }
                }
            }


            unset($array[$key]);
        }

        return $new_array;

    }


    /**
     * Update a document by id
     * @param $id
     * @param array $fields_to_update
     * @return array|bool
     */
    public function updateDocument($id,$fields_to_update = array(),$refresh=false)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'id' => $id,

        ];

        if($refresh) {
            $params = array_merge([
                'refresh' => $refresh
            ],$params);
        }
        $params['body']['doc'] = $fields_to_update;

        try {
            $response =  $this->client->update($params);
        } catch (\Exception $e) {
            $response = false;
        }

        return $response;
    }



    /**
     * Get all the records from a particular search query
     * @param $scroll_id
     * @param $timeout
     * @return array
     */
    public function scrollThroughResults($scroll_id,$timeout)
    {

        $results = [];
        while(true) {
            $response = $this->client->scroll([
                    "scroll_id" => $scroll_id,
                    "scroll" => $timeout
                ]
            );
            if (count($response['hits']['hits']) > 0) {
                $scroll_id = $response['_scroll_id'];
                foreach($response['hits']['hits'] as $values) {
                    $results[]  =$values;
                }
            } else {

                break;
            }

        }

        return $results;
    }


    /**
     * Get the fields names of the elasticsearch index
     * @param $feed_id
     * @return array
     */
    public function getEsFields($feed_id)
    {
        $fields = [];
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'body' => [
                'query'=>[
                    'bool' => [
                        'must'=> [

                            [
                                'match' => [
                                    'feed_id'=>$feed_id
                                ]
                            ]

                        ]
                    ]
                ]
            ]

        ];


        $es_data =  $this->client->search($params);

        foreach($es_data['hits']['hits'] as $data) {
            foreach(array_keys($data['_source']) as $source) {
                if(!isset($fields[$source])) {
                    $fields[$source] = true;
                }

            }
        }
        return array_keys($fields);

    }


    /**
     * Count the records of a given index
     * @return array
     */
    public function countRecords()
    {
        return $this->client->count(['index'=>$this->index_name]);
    }



    /**
     * @param array $data
     * @param $id
     * @return array
     */
    public function indexIntoElasticSearch($data = array(),$refresh=false,$insert_id = '')
    {

        $insertData = [
            'body' => $data,
            'index' => $this->index_name,
            'type' => $this->type_name,
            'refresh'=>$refresh
        ];

        if($insert_id !='') {
            $insertData['id'] = $insert_id;
        }

        return $this->client->index($insertData);
    }


    /**
     * Remove the bulk data
     * @param $inserts
     */
    public  function removeBulkData($ids)
    {
        $params = [];
        foreach($ids as $id) {
            $params['body'][] = [
                'delete' => [
                    '_index' => $this->index_name,
                    '_type' => $this->type_name,
                    '_id'=>$id
                ]
            ];
        }

        if(count($params) > 0 ) {
            $this->client->bulk($params);
        }

    }

    /**
     * A recursive method in order to insert bulk data.
     * @param array $inserts
     * @param array $meta_data
     * @param string $method
     * @param bool $recursion
     */
    public final  function insertBulkData($inserts = [], $meta_data=[],$method=ESImportType::INDEX,$recursion = false,$refresh=false)
    {


        $counter = 1;
        $save_tail = [];

        if($refresh) {
            $params['refresh'] = true;
        }

        foreach($inserts as $insert) {

            $params['body'][] = [
                $method => [
                    '_index' => $this->index_name,
                    '_type' => $this->type_name,
                    '_id'=>$insert['generated_id']

                ]
            ];



            if($method == ESImportType::INDEX) {
                if(count($meta_data) >0) {
                    $insert = array_merge($insert,$meta_data);
                }
                $params['body'][] = $insert;
            }


            if(!$recursion) {

                if ($counter  % DFBUILDER_MAX_ES_BULK_COUNT === 0) {

                    unset($save_tail);
                    $this->client->bulk($params);
                    unset($params);

                } else {

                    $save_tail[] = $insert;

                }
            }
            $counter++;
        }



        /**
         * What if we have rest data and the feed is smaller then 10k?
         * Use recursion and rebulk the other documents
         */
        if($recursion) {
            $this->client->bulk($params);
            unset($save_tail);
        }
        if(isset($save_tail)) {
            $this->insertBulkData($save_tail,$meta_data,$method,true,$refresh);
        }

    }


    /**
     * Clean the string from unwanted characters
     * @param $string
     * @return mixed
     */
    public function cleanEsQueryFields($string)
    {
        return  str_replace("\r","",strip_tags($string));
    }

    /**
     * Base autosuggest search, with ngram filters
     * @param $feed_id
     * @param $phrase
     * @param $field
     * @return array
     */

    public function searchAutosuggest($feed_id,$phrase,$field)
    {
        $params = [
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
                                    'match' => [
                                        $field.'.autocomplete'=>$phrase
                                    ],

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
     * We can use this method to perform multimatch queries.
     * This can be handy when searching a word in a sentence.
     * But this is not configure with an ngram..
     * @param $feed_id
     * @param $term
     * @param array $fields
     * @param $offset
     * @param $aantal_per_pagina
     * @param string $operator
     * @return array
     */
    public function searchMultiMatch($feed_id,$term,$fields = [],$offset,$aantal_per_pagina,$operator='and')
    {
        $params = [
            'from'=>$offset,
            'size'=>$aantal_per_pagina,
            'index' => $this->index_name,
            'type' => $this->type_name,
            'body' => [
                'query'=>[
                    'bool' => [
                        'must'=> [
                            [
                                'match' => [
                                    'feed_id'=>$feed_id
                                ]

                            ],
                            [
                                'multi_match' => [
                                    'query'=>$term,
                                    'operator'=>$operator,
                                    'fields' => $fields
                                ]

                            ]

                        ]
                    ]
                ]


            ]
        ];

        $res = $this->client->search($params);
        return $res;
    }


    /**
     * Search in different operations
     * @param $feed_id
     * @param $term
     * @param $field
     * @param $type
     * @param int $offset
     * @param int $limit
     * @return mixed
     */

    public function categorizeSearchOperations($feed_id,$term,$field,$type,$from=0,$limit=DFBUILDER_DEFAULT_ES_LIMIT, $scroll='', $fields = [])
    {

        $params = [
            'size'=>$limit,
            'index' => $this->index_name,
            'type' => $this->type_name
        ];



        if(!empty($scroll)) {
            $params = array_merge([
                'scroll' => $scroll
            ],$params);

        }

        if($from!=0) {
            $params = array_merge([
                'from' => $from
            ],$params);
        }

        $condition = [];
        switch ($type) {


            /**
             *
             */
            case ConditionSelector::BY_FEED:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    ['term'=>['feed_id'=>$feed_id]]
                                ],
                            ]
                        ]
                    ]
                ];
                break;


            /**
             * Global range query for ES
             */
            case ConditionSelector::GT:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    ['term'=>['feed_id'=>$feed_id]],
                                    ['range' => $this->buildRangeQuery($field,$term,'gt')


                                    ]
                                ],

                            ]
                        ]
                    ]
                ];
                break;
            /**
             * Global range query for ES
             */
            case ConditionSelector::GT_EQ:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    ['term'=>['feed_id'=>$feed_id]],
                                    ['range' => $this->buildRangeQuery($field,$term,'gte')


                                    ]
                                ],

                            ]
                        ]
                    ]
                ];
                break;
            /**
             * Global range query for ES
             */
            case ConditionSelector::LT:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    ['term'=>['feed_id'=>$feed_id]],
                                    ['range' => $this->buildRangeQuery($field,$term,'lt')

                                    ]
                                ],

                            ]
                        ]
                    ]
                ];
                break;

            /**
             * Global range query for ES
             */
            case ConditionSelector::LT_EQ:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    ['term'=>['feed_id'=>$feed_id]],
                                    ['range' => $this->buildRangeQuery($field,$term,'lte')


                                    ]
                                ],

                            ]
                        ]
                    ]
                ];
                break;

            /**
             * ES sees empty results as null
             * The field will not exists then
             */
            case ConditionSelector::IS_NOT_EMPTY:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                // must exactt match
                                'must'=> $this->buildEmptyQuery($field)
                            ]
                        ]
                    ]
                ];
                break;


            /**
             * ES sees empty results as null
             * The field will not exists then
             */
            case ConditionSelector::IS_EMPTY:
                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must_not'=> $this->buildEmptyQuery($field)

                            ]
                        ]
                    ]
                ];

                break;


            /**
             * Global contains query for ElasticSearch
             */
            case ConditionSelector::CONTAIN:
                $condition = [

                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],

                                'must'=> $this->buildContainQuery($term,$field)

                            ]
                        ]
                    ]
                ];
                break;



            case ConditionSelector::CONTAINS_MULTI:
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
                $condition['body']['query']['bool'] = $this->convertMultiParams($term,$field,'should');



                break;


            case ConditionSelector::NOT_CONTAINS_MULTI:
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
                $condition['body']['query']['bool'] = $this->convertMultiParams($term,$field,'must_not');



                break;
            /**
             * Global contains query for ElasticSearch
             */
            case ConditionSelector::NOT_CONTAIN:
                $condition = [

                    'body' => [
                        'query'=>[
                            'bool' => [
                                // must match
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must_not'=> $this->buildContainQuery($term,$field)





                            ]
                        ]
                    ]
                ];

                break;


            case ConditionSelector::EQUALS_MULTI:

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
                $condition['body']['query']['bool'] = $this->convertMultiParams($term,$field.'.keyword','should','match');



                break;

            /**
             * Global equals query for ElasticSearch
             */
            case ConditionSelector::EQUALS:

                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must'=>  $this->buildEqualsQuery($field,$term),
                            ]
                        ]
                    ]
                ];



                break;


            case ConditionSelector::NOT_EQUALS_MULTI:

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
                $condition['body']['query']['bool'] = $this->convertMultiParams($term,$field.'.keyword','must_not','match');


                break;

            /**
             * Global equals query for ElasticSearch
             */
            case ConditionSelector::NOT_EQUALS:


                $condition = [

                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must_not'=> [
                                    // must not match
                                    'match' => [
                                        $field.'.keyword'=>$term
                                    ]
                                ]





                            ]
                        ]
                    ]
                ];



                break;
            /**
             * Global regex query for ElasticSearch
             */
            case ConditionSelector::IS_REGEXP:


                $condition = [
                    'body' => [
                        'query'=>[
                            'bool' => [
                                // must exactt match
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must'=> [
                                    [

                                        [
                                            'regexp'=>
                                                [
                                                    $field.'.keyword'=>
                                                        [
                                                            'value'=>$term
                                                        ]
                                                ]
                                        ],
                                    ]

                                ],
                            ]
                        ]
                    ]
                ];



                break;


            /**
             * Global regex query for ElasticSearch
             */
            case ConditionSelector::NOT_REGEXP:

                $condition = [

                    'body' => [
                        'query'=>[
                            'bool' => [
                                'filter'=>[
                                    'term'=>['feed_id'=>$feed_id]
                                ],
                                'must_not'=> [
                                    // must not match
                                    'regexp'=>
                                        [
                                            $field.'.keyword'=>
                                                [
                                                    'value'=>$term
                                                ]
                                        ]
                                ]

                            ]
                        ]
                    ]
                ];
                break;
        }

        if(count($fields) > 0) {
            $condition['body']['_source'] = $fields;
        }
        $params = array_merge($params,$condition);
        try {
            return $this->client->search($params);
        } catch (\Exception $e) {
           // LoggerFacade::addAlert($e->getMessage(),LogStates::CRITICAL);
            return false;
        }


    }





    /**
     *  If we have multiple values,
     * Convert then to a should query
     * http://stackoverflow.com/questions/43942612/elasticsearch-returns-all-results-with-should-and-term-query/43945227#43945227
     * @param $term
     * @param $field
     * @return array
     */
    protected function convertMultiParams($term,$field,$type='should',$match_type='multi_match', $is_array = false)
    {


        if(!$is_array) {
            $term  = explode ("\n",$term);
        }

        $terms[$type] = [];
        if(is_array($term)) {
            foreach($term as $t) {
                $t = $this->cleanEsQueryFields($t);
                // multi match
                if($match_type == 'multi_match') {
                    $terms[$type][] =   [
                        'multi_match' => $this->buildContainsMultiQuery($t,$field)
                    ];
                    $terms['minimum_should_match'] = 1;


                    // match
                } elseif($match_type == 'match') {
                    $terms[$type][] =   [
                        'match' => [
                            $field=>$t
                        ]
                    ];

                }
            }
        }

        return $terms;
    }



}