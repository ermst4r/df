<?php namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;
use App\DfCore\DfBs\Enum\RuleConditions;


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
  | Copy the value from a field and place it in the new field
  |--------------------------------------------------------------------------
  |
  | Copy the value from a field and place it in the new field
  */


class CopyValueFromFieldStrategy implements iContract
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

        /**
         * Get the map field names
         * and add them to an strtr array
         */

        if(isset($es_record['_source'][$then_field])) {
            $find_values = (isset($then_field_values[0]) ? $then_field_values[0] : []);
            $glue_values = '';
            foreach ($find_values as $find) {
                $glue_values .= $es_record['_source'][$find];
            }
            if (isset($es_record['_source'][$then_field])) {
                $es_record['_source'][$then_field] = $glue_values;
            }
        }
        return $es_record;

    }


    /**
     * @return int
     */
    public function getRuleType()
    {
        return RuleConditions::THEN_COPY_VALUE_FROM_FIELD;
    }
}