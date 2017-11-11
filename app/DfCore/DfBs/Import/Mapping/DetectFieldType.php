<?php


namespace App\DfCore\DfBs\Import\Mapping;


/**
 * A general class what handles the product ids
 * Class ProductId
 * @package App\DfCore\DfBs\Import\Mapping
 */
class DetectFieldType
{

    /**
     * If you want to test whether a string is containing a float, rather than if a variable is a float, you can use this simple little function:
     * @param $f
     * @return bool
     */
    public static function  isfloat($f)
    {
        return ($f == (string)(float)$f);
    }


    /**
     * @param $map_array
     * @param $number_of_records
     * @return mixed
     */
    public static function calculateToMarkAsFloat($map_array,$number_of_records)
    {
        foreach($map_array as $key=>$values) {
            $map_array[$key] = ( $values / $number_of_records * 100 >= 70 ? 'float' : 'text' ) ;
        }
        return $map_array;
    }





}