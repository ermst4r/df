<?php

namespace App\ElasticSearch;

use App\DfCore\DfBs\Enum\ConditionSelector;

/**
 * Class ESRules
 * @package App\ElasticSearch
 */
class ESHot extends  BaseElasticSearch
{
    /**
     * @var string
     */
    private $hot_id_field = 'id';

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
     *
     * Format to readable hands on table data...
     * @param $feed_id
     * @param $offset
     * @param $limit
     * @param $selected_condition
     * @param null $term
     * @param null $field
     * @return mixed
     */
    public function readableHotData($feed_id,$offset,$limit,$selected_condition,$term=null,$field=null, $prefilled_fields=[])
    {


        $get_results = $this->categorizeSearchOperations($feed_id,$term,$field, $selected_condition,$offset,$limit,'',$prefilled_fields);
        $num_of_items = count($get_results['hits']['hits']);
        $data['data'] = [];
        $field_names['id'] = true;
        foreach($get_results['hits']['hits'] as $field_key => $results) {
            $rows = [];
            $_id = $results['_id'];
            foreach($results['_source'] as $field_name=>$field_values) {
                if(!isset($field_names[$field_name]) ) {
                    $field_names[$field_name] = true;
                }
                $rows[] = $field_values;
            }
            array_unshift($rows,$_id);
            // if an user set custom rows
            // restore the array
            if(count($prefilled_fields) >0) {
                $rows = $this->restoreFormattedRows($rows,$prefilled_fields,$field_names);
            }
            $data['data'][] = $rows;
        }



        if(count($prefilled_fields) > 0) {
            array_unshift($prefilled_fields,$this->hot_id_field);
        } else {
            $prefilled_fields =  array_keys($field_names);
        }

        $data['field_names'] = $prefilled_fields;
        $data['show_next'] = $limit  == $num_of_items;
        $data['show_prev'] = ($num_of_items  <= $limit &&  $offset > 0);
        $data['num_of_items'] = $get_results['hits']['total'];

        return $data;
    }


    /**
     * Restore the array for HOT if an user set what fields he want to see.
     * @param $rows
     * @param $prefilled_headers
     * @param $original_headers
     * @return array
     */
    protected function restoreFormattedRows($rows,$prefilled_headers, $original_headers)
    {
        array_unshift($prefilled_headers,$this->hot_id_field);
        $new_rows = [];
        foreach($prefilled_headers as $key=>$header) {
            $new_rows[$key] = (isset($rows[searchInArrayKey($header,$original_headers)]) ? $rows[searchInArrayKey($header,$original_headers)] : null) ;
        }
        return $new_rows;


    }






}