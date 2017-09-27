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
  | Common string replacement actions
  |--------------------------------------------------------------------------
  */
namespace App\DfCore\DfBs\Rules\RuleStrategy\Strategies;


use App\DfCore\DfBs\Enum\CommonRulesEnum;
use App\DfCore\DfBs\Enum\RuleConditions;

class CommonStringActions implements iContract
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
        $textual_filter =  (isset($then_field_values[0]) ? $then_field_values[0] : '');

        if(isset($es_record['_source'][$then_field])) {

            switch ($textual_filter) {

                case CommonRulesEnum::FIRST_CHARACTER_UPPERCASE:

                    $es_record['_source'][$then_field] = ucfirst($es_record['_source'][$then_field]);
                break;

                case CommonRulesEnum::WORD_UPPER_CASE:
                    $es_record['_source'][$then_field] = ucwords($es_record['_source'][$then_field]);
                break;

                case CommonRulesEnum::LOWERCASE_ALL:

                    $es_record['_source'][$then_field] = strtolower($es_record['_source'][$then_field]);
                break;

                case CommonRulesEnum::UPPERCASE_ALL:
                    $es_record['_source'][$then_field] = strtoupper($es_record['_source'][$then_field]);
                break;

                case CommonRulesEnum::REMOVE_NON_NUMERIC_CHARACTERS:
                    $es_record['_source'][$then_field] =  preg_replace("/[^0-9]/", "",  $es_record['_source'][$then_field] );
                break;

                case CommonRulesEnum::REMOVE_LINE_BREAKS:
                    $es_record['_source'][$then_field] = preg_replace( "/\r|\n/", "",  $es_record['_source'][$then_field] );
                break;

                case CommonRulesEnum::REMOVE_HTML:
                    $es_record['_source'][$then_field] = strip_tags($es_record['_source'][$then_field]);
                break;
            }
        }
        return $es_record;

    }

    public function getRuleType()
    {
        return RuleConditions::THEN_FIND_REPLACE_OTHER_FIELD;
    }



}