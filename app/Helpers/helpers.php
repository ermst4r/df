<?php

/**
 * @param $current
 * @param string $cssClass
 * @return string
 */
function getCurrentRouteName()
{

    return Route::currentRouteName();
}



/**
 * Let us create the index name
 * @param $id
 * @return string
 */
function createEsIndexName($id,$type=\App\DfCore\DfBs\Enum\ESIndexTypes::TMP)
{
    switch($type) {
        case \App\DfCore\DfBs\Enum\ESIndexTypes::TMP:
            return 'index-'.$id;
        break;

        case \App\DfCore\DfBs\Enum\ESIndexTypes::CHANNEL:
            return 'channel-'.$id;
        break;

        case \App\DfCore\DfBs\Enum\ESIndexTypes::ADWORDS:
            return 'adwords-'.$id;
        break;

        case \App\DfCore\DfBs\Enum\ESIndexTypes::BOL:
            return 'bol-'.$id;
        break;
    }

}



function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

function debug_string($custom_msg,\Exception $e)
{
    return sprintf($custom_msg.' Exception: message %s, File: %s Line: %s', $e->getMessage(), $e->getFile(),$e->getLine());
}



function count_backup_templates($ad_id)
{
    $AdwordsAdRepository = new \App\Entity\Repository\AdwordsAdRepository(new \App\Entity\AdwordsAd());
    return $AdwordsAdRepository->countAds($ad_id);
}


/**
 * @param $channel_feed_id
 * @return mixed
 */
function es_cat_field_name($id,$url_key)
{
    return (string) $url_key.'_'.$id;
}


/**
 * Show us the difference between 2 strings
 * @param $string1
 * @param $string2
 * @return float|int
 */
function levenshtein_difference($string1,$string2)
{
    $percentage  = 0;
    $string1 = strtolower($string1);
    $string2 = strtolower($string2);

    if(strlen($string1) >= 255 || strlen($string2) >=255) {
        return $percentage;
    }

    $l = levenshtein($string1,$string2);
    $p_difference = strlen($string1) -$l;
    if(strlen($p_difference) > 0  ||  strlen($string1) >0) {
        $percentage = ($p_difference / strlen($string1) * 100 );
    }
    return $percentage;

}


/**
 * @param $array
 * @return mixed
 */
 function remove_first_element_from_assoc_array($array)
 {

     foreach(array_keys($array) as $key ) {
        unset($array[$key]);
        break;
     }
     return $array;
 }


/**
 * Search in the array key and return its index...
 * @param $needle
 * @param $array
 * @return bool|int
 */
 function searchInArrayKey($needle,$array)
{
    $counter = 0;
    $key = false;
    foreach(array_keys($array) as $values) {
        if($values == $needle) {
            $key = $counter;
            break;
        }

        $counter ++;
    }
    return $key;
}

/**
 * Remove the meta fields for what the user isn't allow to see.
 * We can use this helper in the spreadsheets for example...
 * @param $fields
 * @return mixed
 */
function removeMetaFields($fields)
{
    foreach(config('dfbuilder.es_live_to_exclude') as $fields_to_exclude) {
        $search_value = array_search($fields_to_exclude,$fields);
        if($search_value !== false) {
            unset($fields[$search_value]);
        }
    }
    return $fields;
}

/**
 * @param $textarea
 * @return mixed
 */
function replace_textarea_to_newline($textarea)
{
    return str_replace("\r\n","\n",$textarea);
}

function nbsp_to_space($string) {
    return str_replace('&nbsp;',' ',$string);
}