<?php

namespace App\ElasticSearch;


/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESBol extends  BaseElasticSearch
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




}