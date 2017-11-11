<?php
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


/*
  |--------------------------------------------------------------------------
  | Search a value in another field and explode / implode values
  |--------------------------------------------------------------------------
  |
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\RuleConditions;

class SplitFieldStrategy implements iContract
{
    /**
     * Each rule has is own manner of handling.
     * Per strategy we handle the rules depending on the form input what has been given in the front-end.
     * @param $es_record
     * @param $then_field
     * @param $then_field_values
     * @param $then_spacing
     * @return array
     */
    public function handle($es_record, $then_field, $then_field_values,$then_spacing)
    {

        $from_field = (isset($then_field_values[0]) ? $then_field_values[0] : '');
        $seperator = (isset($then_field_values[1]) ? $then_field_values[1] : '');
        $from = (isset($then_field_values[2]) ? $then_field_values[2] : 0);
        $to = (isset($then_field_values[3]) ? $then_field_values[3] : 0);
        $concatenate_with = (isset($then_field_values[4]) ? $then_field_values[4] : '');





        if(isset($es_record['_source'][$from_field])) {
            $concat_length = strlen($concatenate_with) -  (strlen($concatenate_with) *2);
            preg_match_all("/" . preg_quote($seperator, '/') . "/",$es_record['_source'][$from_field],$matches);
            $chunk_string = explode($seperator,$es_record['_source'][$from_field]);
            $glue = '';
            for($i = $from -1; $i < $to; $i++) {
                if(isset($chunk_string[$i])) {
                    $glue.= $chunk_string[$i].$concatenate_with;
                }
            }
            $glue = substr($glue,0,$concat_length);
            $es_record['_source'][$from_field] = $glue;
        }

        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_SPLIT_FIELD;
    }



}