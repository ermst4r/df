<?php
namespace App\DfCore\DfBs\Adwords;
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */


class AdgroupPreview
{


    private static $token_seperator = '===';
    /**
     * Give us the tags back from the adgroup
     * @param $adgroup_name
     * @return mixed
     */
    public static function grepTags($adgroup_name)
    {
        preg_match_all("/{(.*?)}/si", $adgroup_name, $match);
        return $match[1];

    }


    /**
     * @param $a
     * @return array
     */
    public static  function recursivePrepareAdwordsData($a)
    {
        $returnArray = [];
        if(isset($a['name'])) {
            foreach($a['name']['buckets'] as $v) {
                $returnArray[$v['key']]  = self::recursivePrepareAdwordsData($v);
            }
        }
        return $returnArray;
    }


    /**
     * Give us the new arrray back with a bracked identifier.
     * @param $array
     * @return array
     */
    public static function changeEsFieldNames($array)
    {

        $new_array = [];
        foreach($array as $key=>$values) {
            if(!is_array($values))
               $new_array['{' . $key . '}'] = $values;


        }
        return $new_array;
    }



    /**
     * create the generic name for ad adgroup or campaign
     * @param $fields_to_groep
     * @param $group_names
     * @param $adgroup_name
     * @return array
     */
    public static function createAdwordsGenericName($fields_to_groep,$group_names,$adgroup_name)
    {
        $results = [];
        $matchQuery = [];

       foreach($group_names as $a) {

           $ad_names = explode(self::$token_seperator,$a);
           if(count($fields_to_groep) != count($ad_names)) {
               continue;
           }
           $format_array = [];
           foreach($ad_names as $key => $format_names) {
               if(isset($fields_to_groep[$key])) {
                   $format_array["{".$fields_to_groep[$key]."}"] = $format_names;
                   $matchQuery[$fields_to_groep[$key]] = $format_names;

               }
           }
           if(count($format_array) > 0 ) {
               $results['matchQuery'][] = $matchQuery;
               $results['names'][] =  strtr($adgroup_name,$format_array);
           }
       }

       return $results;
    }
    /**
     * @param $array
     * @param $prev
     * @return array
     */
    public static function recursiveParseAdwordsArray($array,$prev)
    {
        $seperator = self::$token_seperator;
        $returnArray = [];
        if(is_array($array) && isset($array)) {
            foreach (array_keys($array) as $v) {
                if($prev == '') {
                    $returnArray = array_merge($returnArray,  self::recursiveParseAdwordsArray($array[$v],$prev.$v));
                } else {
                    $returnArray[] = $prev.$seperator.$v;
                    $returnArray = array_merge($returnArray,  self::recursiveParseAdwordsArray($array[$v],$prev.$seperator.$v));
                }
            }
        }

        return $returnArray;
    }


}