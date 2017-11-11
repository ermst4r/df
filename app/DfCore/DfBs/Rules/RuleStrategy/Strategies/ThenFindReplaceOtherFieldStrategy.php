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
  | Search in other field and replace value in current field
  |--------------------------------------------------------------------------
  |
  | Search in other field and replace value in current field
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\RuleConditions;

class ThenFindReplaceOtherFieldStrategy extends AbstractBaseRule implements iContract
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

        $field = (isset($then_field_values[0]) ? $then_field_values[0] : '');
        $find_value = (isset($then_field_values[1]) ? $then_field_values[1] : '');
        $replace_value = (isset($then_field_values[2]) ? $then_field_values[2] : '');
        $field_values = $this->formatFeedFields($es_record);

        $find_value = strtr($find_value, $field_values);
        $replace_value = strtr($replace_value, $field_values);

        if(isset($es_record['_source'][$then_field])) {
            if(isset($es_record['_source'][$field])) {
                $es_record['_source'][$then_field]  =  preg_replace("/" . preg_quote($find_value, '/') . "/", $replace_value,$es_record['_source'][$field]);
            }
        }
        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD;
    }



}