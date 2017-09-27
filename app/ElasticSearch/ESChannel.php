<?php

namespace App\ElasticSearch;


/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESChannel extends  BaseElasticSearch
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
     * Get all channel data
     * @return array
     */
    public function getChannelData()
    {
        $params = [
            'index' => $this->index_name,
            'type' => $this->type_name,
            'scroll'=>'1m'
        ];

      $results =   $this->client->search($params);
      $scanned_results = $this->scrollThroughResults($results['_scroll_id'],'1m');
      $merged_results[] = $this->changeArrayStructure(array_merge($results['hits']['hits'],$scanned_results));
      return $merged_results;

    }



}