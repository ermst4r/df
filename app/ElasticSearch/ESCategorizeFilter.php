<?php

namespace App\ElasticSearch;


use App\DfCore\DfBs\Log\LoggerFacade;

class ESCategorizeFilter extends  BaseElasticSearch
{


    public function getResultsFromCatIds($cat_id)
    {

        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll'=>'1m',
            'body' => [
                'query'=>[
                    'bool' => [
                        'must'=> [

                            [
                                'match' => [
                                    'cat_ids'=>$cat_id
                                ]
                            ]

                        ]
                    ]
                ]
            ]

        ];
        try {
            return $this->client->search($params);

        } catch (\Exception $e) {

            LoggerFacade::addAlert($e->getMessage());
            return false;
        }



    }

    /**
     * @param $feed_id
     * @param $phrase
     * @param $field
     * @param $condition
     * @param array $category_details
     * @return array|mixed
     */
    public function categoryToES($feed_id,$phrase,$field,$condition,$category_details=[])
    {
        $get_results = $this->categorizeSearchOperations($feed_id,$phrase,$field, $condition,0,DFBUILDER_DEFAULT_ES_LIMIT,'1m');

        if($get_results == false) {
            return [];
        }
        $category_meta = @json_decode($category_details['category_meta'],true);

        $scanned_results = $this->scrollThroughResults($get_results['_scroll_id'],'1m');

        $merged_results = $this->changeArrayStructure(
            array_merge($get_results['hits']['hits'],$scanned_results),
            [
                'meta_internal_cat_id'=>$category_details['category_id'],
                'meta_shop_category_name'=>$category_details['category_name'],
                'category_meta'=>$category_meta
            ],
            [
                $field => $category_details['category_name']
            ]
        );

        return $merged_results;
    }



    /**
     * @param $feed_id
     * @param bool $has_category_filter
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function searchCategorizeMapped($feed_id,$has_category_filter = [], $offset = 0, $limit = 10, $search = '',$field = '')
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
                                        $has_category_filter
                                ]

                            ],

                        ]
                    ]
                ]

            ]
        ];

        if($search != '') {
            $search_param = [
                'multi_match' => [
                    'query'=>$search,
                    'operator'=>'and',
                    'fields' => $field
                ]

            ];


            $params['body']['query']['bool']['must'][0][] = $search_param;

        }

        return $this->client->search($params);
    }

}