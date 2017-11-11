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
  | Combine field rule
  |--------------------------------------------------------------------------
  | Add the value's from the feed value and append them
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\RuleConditions;

class CombineFieldValue  extends AbstractBaseRule implements iContract
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
        if(isset($es_record['_source'][$then_field])) {

                $field_values = $this->formatFeedFields($es_record);
                $find_values = (isset($then_field_values[0]) ? $then_field_values[0] : '');
                $find_values = strtr($find_values, $field_values);
                $glue_values = $find_values;
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
        return RuleConditions::THEN_COMBINE_FEED_VALUE;
    }



}