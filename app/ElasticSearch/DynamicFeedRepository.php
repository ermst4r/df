<?php

namespace App\ElasticSearch;
use App\DfCore\DfBs\Enum\ImportType;
use App\DfCore\DfBs\Enum\LogStates;
use App\DfCore\DfBs\Import\Mapping\MappingValidator;
use App\DfCore\DfBs\Log\LoggerFacade;


/**
 * Class DynamicFeedRepository
 * @package App\Entity\ElasticSearch
 */
class DynamicFeedRepository extends BaseElasticSearch
{


    /**
     * DynamicFeedRepository constructor.
     * @param $index_name
     * @param $type_name
     */
    public function __construct($index_name, $type_name)
    {
        parent::__construct($index_name, $type_name);
    }









    /**
     *  Create a dynamic mapping per feed.
     * Give the fields of the feed and the mapping will be created
     * @param $mappings
     */
    public  function createDynamicMapping($mappings)
    {



        $client = $this->client;
        if(!$client->indices()->exists(['index'=>$this->index_name])) {
            /**
             * We just need ES, for basic things.
             * Because we work with big feeds, the user needs quick interaction, searching and faceting
             */

            $client->indices()->create([
                'index' => $this->index_name,
                'body'=> [
                    'settings'=>[
                        'analysis'=>[
                            'filter'=>
                                [
                                    'autocomplete_filter'=>
                                        [
                                            'type'=>'edge_ngram',
                                            'min_gram'=>1,
                                            'max_gram'=>20
                                        ],
                                ],
                            'analyzer'=>
                                [

                                    'autocomplete'=>
                                        [
                                            'type'=>'custom',
                                            'tokenizer'=>'standard',
                                            'filter'=>
                                                [
                                                    'lowercase',
                                                    'autocomplete_filter',

                                                ]

                                        ]
                                ]
                        ]
                    ]
                ]
            ]);
        }

        $params['index']  = $this->index_name;
        $params['type']  = $this->type_name;
        foreach($mappings as $mapping_name => $mapping_value) {

            /**
             * Let us decide what mapping we use
             * Float or text ?
             */
            switch($mapping_value)  {
                case 'float':
                    $params['body'][$this->type_name] = array(
                        'properties'=>array(
                            MappingValidator::formatMapping($mapping_name)=>array(
                                'type'=> $mapping_value,
                            )));
                break;

                default:
                    $params['body'][$this->type_name] = array(
                        'properties'=>array(
                            MappingValidator::formatMapping($mapping_name)=>array(
                                'type'=> $mapping_value,
                                'fields'=> [
                                    'keyword' => ['type'=>'keyword'],
                                    'autocomplete' => ['type'=>'text','analyzer'=>'autocomplete'],
                                ]
                            )));

            }


            try {

                $client->indices()->putMapping($params);
            } catch (\Exception $e) {
               // LoggerFacade::addAlert($e->getMessage() . " cannot create mapping...",LogStates::CRITICAL);
                return false;
            }


        }

    }



}