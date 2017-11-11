<?php


namespace App\DfCore\DfBs\Import\Mapping;
use App\DfCore\DfBs\Enum\ImportType;
use App\Entity\Repository\CsvMappingRepository;
use App\Entity\Repository\XmlMappingRepository;
use Illuminate\Http\Request;

/**
 * A general mapping class to handle function from xml and csv mapping
 * Class Mapping
 * @package App\DfCore\DfBs\Import\Mapping
 */
class Mapping
{


    /**
     * Get the feed field from the mapping from the user.
     * e.g. we want to know what product id field the user has mapped, and matching the feed node
     * @param $es_array
     * @param $mapped_fields
     * @param string $field
     * @return mixed
     */
    public static function getFeedFieldFromMapping($feed_fields,$mapped_fields,$field='product_id')
    {

       foreach($feed_fields as $feed_field) {
           $feed_field = MappingValidator::formatMapping($feed_field);
           if(isset($mapped_fields[$feed_field]) && key($mapped_fields[$feed_field]) == $field) {
                return $feed_field;
           }
       }

    }

    /**
     * @param $fields_to_map
     * @return array
     */
    private  static  function fieldsToMapToComputableArray($fields_to_map)
    {
        $returnArray = [];
        foreach($fields_to_map as $f) {
            $returnArray[$f->field] = true;
        }
        return $returnArray;
    }


    /**
     * Help the user what fields can be autodetected
     * @param $mapping
     * @param $fields_to_map
     */
    public  static  function prefillMapping($mapping,$fields_to_map)
    {
        $showMapping = [];
        $fields_to_map = self::fieldsToMapToComputableArray($fields_to_map);
        foreach($mapping as $m) {
            $showMapping[$m] = false;
            foreach ($fields_to_map as $f_key => $f_value) {
                if(strpos(strtolower($m),strtolower($f_key))!== false) {
                    $showMapping[$m] = $f_key;
                    unset($fields_to_map[$f_key]);
                    continue;
                }
            }


        }
        return $showMapping;

    }


    /**
     * Prepare the csv mapping and make an array to insert into the database
     * @param Request $request
     * @return array
     */
    public static function prepareToSaveCsvMapping(Request $request)
    {
        $data_to_insert = [];
        $number_of_fields = (int) $request->get('number_of_fields');
        for($i = 0; $i < $number_of_fields; $i++) {
            if(!is_null($request->get('mapped_field'.$i))) {
                $data['mapped_field_name'] = MappingValidator::formatMapping($request->get('mapped_field_name'.$i));
                $data['mapped_csv_name'] = $request->get('mapped_field'.$i);
                $data['csvindex'] = $i;
                $data['fk_feed_id'] = $request->get('fk_feed_id');
                $data_to_insert[] = $data;

            }
        }
        return $data_to_insert;
    }


    /**
     * Handle the xml mapping, to prepare to insert into the database
     * @param Request $request
     * @return array
     */
    public static function prepareXmlMapping(Request $request)
    {

        $data_to_insert = [];
        $number_of_fields = (int) $request->get('number_of_fields');

        for($i = 0; $i < $number_of_fields; $i++) {
            if(!is_null($request->get('mapped_field'.$i))) {
                $data['xml_map_name'] = MappingValidator::formatMapping($request->get('mapped_field_name'.$i));
                $data['mapped_xml_name'] = $request->get('mapped_field'.$i);
                $data['fk_feed_id'] = $request->get('fk_feed_id');
                $data_to_insert[] = $data;
            }
        }

        return $data_to_insert;
    }


    /**
     * A simple factory for us to check if the feed is mapped for different formats
     * @param CsvMappingRepository $csvMappingRepository
     * @param XmlMappingRepository $xmlMappingRepository
     * @param $type
     * @param $feed_id
     * @return bool
     */
    public  static function isMappedFactory(CsvMappingRepository $csvMappingRepository, XmlMappingRepository $xmlMappingRepository, $type,$feed_id)
    {
        switch($type) {
            case ImportType::CSV:
            case ImportType::TXT:
                return $csvMappingRepository->isMapped($feed_id);
            break;

            case ImportType::XML:

                return $xmlMappingRepository->isMapped($feed_id);
            break;
            default:
                return false;
        }

    }


    /**
     * a Simple factory to see what field is mapped
     * @param CsvMappingRepository $csvMappingRepository
     * @param XmlMappingRepository $xmlMappingRepository
     * @param $type
     * @param $feed_id
     * @return bool
     */
    public static function plainedMappedFactory(CsvMappingRepository $csvMappingRepository, XmlMappingRepository $xmlMappingRepository, $type,$feed_id)
    {
        switch($type) {
            case ImportType::CSV:
            case ImportType::TXT:
                return  $csvMappingRepository->getPlainMappedFields($feed_id);
                break;
            case ImportType::XML:
                return $xmlMappingRepository->getPlainMappedFields($feed_id);
                break;
            default:
                return false;
        }
    }


    /**
     * @param $rows
     * @return mixed
     */
    public static function formatFeedKeys($rows) {
        foreach($rows as $key=>$values) {
            $rows[MappingValidator::formatMapping($rows)] = $values;
        }
        return $rows;
    }

    /**
     * @param $rows
     * @return mixed
     */
    public static function formatFeedValues($rows) {
        foreach($rows as $key=>$values) {
            $rows[$key] = MappingValidator::formatMapping($values);
        }
        return $rows;
    }

    public static function addChannelMapping($channel_headers,$get_tmp_mapping)
    {

        foreach(array_values($channel_headers) as $channel_values ) {

            if(isset($get_tmp_mapping[$channel_values])) {
                $new_key = array_search($channel_values,$channel_headers);
                if($new_key !== false) {
                    $get_tmp_mapping[$new_key] = $get_tmp_mapping[$channel_values];
                }
            }
        }
        return $get_tmp_mapping;
    }


    /**
     * @param $channel_headers
     * @param $get_tmp_mapping
     * @return mixed
     */
    public static function attachExtraChannelMapFields($channel_headers,$get_tmp_mapping)
    {
        foreach($channel_headers as $channel_field_name => $original_field_name) {
            if(!isset($get_tmp_mapping[$channel_field_name])) {
                $get_tmp_mapping[$channel_field_name] = $get_tmp_mapping[$original_field_name];
            }
        }
        return $get_tmp_mapping;
    }


    /**
     * @param $all_products
     * @param $channel_headers
     * @return mixed
     */
    public static function prefillChannelIndex($all_products,$channel_headers)
    {
        foreach($all_products as $key=> $enrich){
            foreach($channel_headers as $channel_field_name=>$index_field_name){
                $all_products[$key]['_source'][$channel_field_name] =$all_products[$key]['_source'][$index_field_name];
            }
        }
        return $all_products;
    }





}