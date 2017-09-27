<?php


namespace App\DfCore\DfBs\Import\Mapping;


/**
 * A general class what handles the product ids
 * Class ProductId
 * @package App\DfCore\DfBs\Import\Mapping
 */
class ProductId
{

    /**
     * @param $rows
     * @param $composite_keys
     * @return mixed
     */
    public static function generateCompositeKey($rows,$composite_keys = [])
    {

        $generated_id = '';
        foreach(array_keys($composite_keys) as $composite_key) {
            if(isset($rows[$composite_key])) {
                $generated_id.=  $rows[$composite_key];
            }
        }
        $rows['generated_id'] = md5($generated_id);
        return $rows;
    }


    /**
     * @param $import_data
     * @param array $mapped_fields_from_user
     * @return mixed
     */
    public static function generateNormalId($import_data,$mapped_fields_from_user = [])
    {
        if(isset($mapped_fields_from_user[DFBUILDER_DEFAULT_ID_NAME])) {
            $import_data['generated_id'] = $import_data[$mapped_fields_from_user[DFBUILDER_DEFAULT_ID_NAME]];
        }
        return $import_data;
    }


    private static function formatKeys($rows,$seperator = '.') {
        foreach($rows as $key=>$value) {
            $rows[MappingValidator::formatMapping($key,$seperator)] = $value;
        }
        return $rows;
    }

}