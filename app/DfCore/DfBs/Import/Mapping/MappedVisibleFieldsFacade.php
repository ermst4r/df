<?php
/**
 * Created by PhpStorm.
 * User: erm
 * Date: 30-03-17
 * Time: 16:05
 */

namespace App\DfCore\DfBs\Import\Mapping;


use App\Entity\Csvmapping;
use App\Entity\Repository\CsvMappingRepository;
use App\Entity\Repository\XmlMappingRepository;
use App\Entity\Xmlmapping;

/**
 * Class MappedVisibleFieldsFacade
 * @package App\DfCore\DfBs\Import\Mapping
 */
class MappedVisibleFieldsFacade
{

    /**
     * Transform the mapped fields to the fields of the feed
     * ONLY THE REQUIRED FIELDS!!
     * @param $get_feed
     * @param $feed_fields
     * @return array
     */
    public static function getFeedFieldsFromMapping($get_feed,$feed_fields)
    {
        $transformed_fields = [];
        $mapped_fields = Mapping::plainedMappedFactory(new CsvMappingRepository(new Csvmapping()),new XmlMappingRepository(new Xmlmapping()),$get_feed->feed_type,$get_feed->id);


        foreach(config('dfbuilder.required_fields_to_map') as $required_fields){
            $transformed_fields[$required_fields] = Mapping::getFeedFieldFromMapping($feed_fields,$mapped_fields,$required_fields);
        }
        return $transformed_fields;

    }
}