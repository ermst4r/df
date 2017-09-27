<?php

namespace App\ElasticSearch;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Log\LoggerFacade;

/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESAdwords extends  BaseElasticSearch
{

    /**
     * ESHot constructor.
     * @param $index_name
     * @param $type_name
     */
    public function __construct($index_name, $type_name)
    {
        parent::__construct($index_name, $type_name);
    }


    /**
     * Get all the products from the feed by id.
     * @param $feed_id
     * @return bool|mixed
     */
    public function getAllProducts($feed_id)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll' => '1m',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            ['term' => ['feed_id' => $feed_id]]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $results =  $this->client->search($params);
            $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
            return $this->changeArrayStructure(array_merge($results['hits']['hits'],$scanned_results));
        } catch (\Exception $e) {
           // LoggerFacade::addAlert($e->getMessage());
            return false;
        }



    }






    /**
     * Build a query with aggegrations...
     * @param $fields
     * @param array $agg_query
     * @return array|bool
     */
    public function buildAggQueryWithCondition($fields=[],$agg_query=[])
    {
        $field_mapping = $this->getFeedMapping();
        $matchQuery = [];
        foreach($fields as $key=>$values) {
           $matchQuery[] =  ['match' => [ $key.$this->getFieldType($key,$field_mapping)=>$values]];
        }
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll' => '1m',
        ];

        if(count($agg_query) >0 ) {
            $params['body']=  $agg_query;
        }

        $params['body']['query']['bool']['must'] = $matchQuery;
        try {

            if(count($agg_query) > 0 ) {
                // show aggegrations..
                return $this->client->search($params);

            } else {
                // show records with scroll id
                $results =  $this->client->search($params);
                $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
                return $this->changeArrayStructure(array_merge($results['hits']['hits'],$scanned_results));

            }
        } catch (\Exception $e) {

            //LoggerFacade::addAlert($e->getMessage());
            return false;
        }




    }


    /**
     * @param $json_query
     * @return mixed
     */
    public function buildAggQuery($json_query)
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
        ];
        $params['body'] = $json_query;
        try {
           $res =  $this->client->search($params);
           return $res['aggregations'];
        } catch (\Exception $e) {

           // LoggerFacade::addAlert($e->getMessage(),LogStates::CRITICAL);
            return false;
        }

    }



    private function getFieldType($needle,$field_mapping)
    {
        $field_type = 'text';
        if(isset($field_mapping[$needle])) {
            $field_type = $field_mapping[$needle];
        }

        if($field_type == 'text') {
            return '.keyword';
        } else {
            return '';
        }
    }

    /**
     * @param $fields
     * https://stackoverflow.com/questions/44948136/php-dynamically-create-multidimensional-array-keys
     * @return array
     */
    public function esAggBuilder($fields,$ag_type=0)
    {


        $aggs = [];
        $field_mapping = $this->getFeedMapping();
        if(count($fields) >= 1) {
            $first_elem = $fields[0].$this->getFieldType($fields[0],$field_mapping);
            array_splice($fields,0,1);
            $ag = null;
            $aggs['aggs']['name'] = ['terms'=>
                [
                'field'=>$first_elem,
                 'size'=>9999
                ]
            ];



            $counter = 0;
            foreach($fields as $ex_fields) {
                $glue = ['name'=>['terms'=>['field'=>$ex_fields.$this->getFieldType($ex_fields,$field_mapping), 'size'=>9999]]];
                if($counter == 3 ) {
                    $aggs['aggs']['name']['aggs']['name']['aggs']['name']['aggs']['name']['aggs']  = $glue;
                }
                if($counter == 2 ) {
                    $aggs['aggs']['name']['aggs']['name']['aggs']['name']['aggs']  = $glue;
                }
                if($counter == 1 ) {
                    $aggs['aggs']['name']['aggs']['name']['aggs'] = $glue;
                }
                if($counter == 0) {
                    $aggs['aggs']['name']['aggs']  = $glue;
                }
                $counter ++;
            }
        }

        return $aggs;



    }




}