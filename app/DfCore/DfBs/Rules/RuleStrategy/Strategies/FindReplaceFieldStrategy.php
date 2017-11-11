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
  | Find and replace per field
  |--------------------------------------------------------------------------
  |
  | Search by the feed value and replace by the feed value
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\RuleConditions;

class FindReplaceFieldStrategy implements iContract
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
        $find_values = (isset($then_field_values[0]) ? $then_field_values[0] : []);
        $replace_values = (isset($then_field_values[1]) ? $then_field_values[1] : []);
        $replacement = [];

        /**
         * combine fields
         *
        */
        if(isset($es_record['_source'][$then_field])) {
            foreach ($find_values as $find) {
                foreach ($replace_values as $replace) {
                    if (isset($es_record['_source'][$replace])) {
                        $replacement[$find][] = $es_record['_source'][$replace];
                    }
                }
            }


            /**
             * search in the fieldname
             * if the searched value exists combine values
             */
            foreach ($replacement as $field_name => $rep_values) {
                if (isset($es_record['_source'][$field_name])) {
                    if (strpos($es_record['_source'][$then_field], $es_record['_source'][$field_name]) !== false) {
                        $es_record['_source'][$then_field] = str_replace($es_record['_source'][$field_name], implode(' ', $rep_values), $es_record['_source'][$then_field]);
                    }
                }
            }
        }

        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_FIND_AND_REPLACE;
    }



}