<?php

namespace App\ElasticSearch;
use App\DfCore\DfBs\Enum\ESImportType;
use App\DfCore\DfBs\Enum\ESIndexTypes;


/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESCopyIndex extends  BaseElasticSearch
{

    /**
     * @param $id
     * @param $feed_id
     * @param $rule_products
     */
    public  static  function copyIndexWithRules($id,$feed_id,$rule_products,$index_type)
    {

        $tmp_index_name =  createEsIndexName($feed_id,ESIndexTypes::TMP);
        $index_name = createEsIndexName($id,$index_type);
        $tmp_index = new DynamicFeedRepository($tmp_index_name, DFBUILDER_ES_TYPE);
        $es_index = new DynamicFeedRepository($index_name, DFBUILDER_ES_TYPE);


        if($es_index->client->indices()->exists(['index'=>$index_name])) {
            $es_index->deleteIndex();
        }

        /**
         * Clone index
         */
        $tmp_feed_mapping_with_types = $tmp_index->getFeedMapping(false);
        $es_index->createDynamicMapping($tmp_feed_mapping_with_types);
        $inserts = [];
        foreach($tmp_index->getAllDocuments(true) as $es_data) {
            if(isset($rule_products[$es_data['_id']])) {
                $es_data['_source'] = $rule_products[$es_data['_id']]['_source'];
            }
            $inserts[] = $es_data['_source'];
            //$adwords_index->indexIntoElasticSearch($es_data['_source'],true,$es_data['_id']); // F*cking slow bro...
        }
        $es_index->insertBulkData($inserts,[],ESImportType::INDEX,false,true);

    }




}